<?php


class Basic
{
    /**
     * @var array
     */
    private $colors = ['red', 'green', 'blue', 'white', 'black', null];

    /**
     * @param int $numberOfItems
     * @param string $jsonFileName
     * @return string|bool
     */
    public function generateRandomItems($numberOfItems, $jsonFileName)
    {
        if ($numberOfItems <= 0) {
            echo "Number of items to generate must be greater than zero";

            return false;
        }

        $products = new Products();
        for ($i = 0; $i < $numberOfItems; $i++) {
            if ($i % 2) {
                $product = new Product($jsonFileName);
            } else {
                try {
                    $product = new ProductVariation($jsonFileName, $this->colors[rand(0, count($this->colors) - 1)]);
                } catch (UndefinedVariantColor $e) {
                    echo "{$e->getMessage()}\n";
                    continue;
                }
            }
            $products->append($product);
        }

        if ($products->count() === 0) {
            echo "Number of generated items is zero";

            return false;
        }

        $jsonContent = json_encode($products, JSON_PRETTY_PRINT);

        $uniqueId = uniqid();
        file_put_contents($this->getFileName($uniqueId), $jsonContent);

        return $uniqueId;
    }

    /**
     * @param string $uniqueId
     */
    public function showItems($uniqueId)
    {
        $fileName = $this->getFileName($uniqueId);

        if (false === file_exists($fileName)) {
            $this->generateRandomItems(20, '.' . DIRECTORY_SEPARATOR . 'product.json');
        }

        $products = new Products();

        $productsArray = json_decode(file_get_contents($fileName), true);
        foreach ($productsArray as $productArray) {

            if (isset($productArray['color'])) {
                $product = new ProductVariation(null, $productArray['color'], $productArray);
            } else {
                $product = new Product(null, $productArray);
            }
            $products->append($product);
        }


        for ($products->rewind(); $products->valid(); $products->next()) {
            $stringContent = $products->current();
            echo $stringContent;
        }
    }

    /**
     * @param string $uniqueId
     * @return bool
     */
    public function deleteProducts($uniqueId)
    {
        $fileName = $this->getFileName($uniqueId);
        if (false === file_exists($fileName)) {
            return true;
        }

        return unlink($fileName);
    }

    /**
     * @param string $uniqueId
     * @return string
     */
    private function getFileName($uniqueId)
    {
        return '.' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . $uniqueId . '.json';
    }
}
