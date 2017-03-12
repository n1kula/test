<?php


class ProductVariation extends Product
{
    /**
     * @var string
     */
    protected $color;

    /**
     * ProductVariation constructor.
     * @param string $jsonFileName
     * @param string $color
     * @param null $data
     * @throws UndefinedVariantColor
     */
    public function __construct($jsonFileName, $color, $data = null)
    {
        if (null === $color || false === is_string($color)) {
            throw new UndefinedVariantColor("Undefined variant color");
        }

        parent::__construct($jsonFileName, $data);
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }
}
