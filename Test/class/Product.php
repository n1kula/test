<?php

class Product implements Item, JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var float
     */
    protected $amount;

    /**
     * Product constructor.
     * @param string $jsonFileName
     *
     * @param null $data
     * @throws ProductFileNotFound
     */
    public function __construct($jsonFileName, $data = null)
    {
        if (false === file_exists($jsonFileName) && $data === null) {
            throw new ProductFileNotFound();
        }

        if (null === $data) {
            //read file
            $productString = file_get_contents($jsonFileName);
            $productArray = json_decode($productString, true);
        } else {
            //use data from array
            $productArray = $data;
        }

        $this->id = $productArray['id'];
        $this->name = $productArray['name'];
        $this->price = $productArray['price'];
        $this->quantity = $productArray['quantity'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param int $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return ($this->amount = $this->quantity * $this->price);
    }

    public function __toString()
    {
        $ret = '';
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            if ($propName === 'amount') {
                continue;
            }

            $ret .= sprintf('%s: %s', $propName, $this->$propName);
            $ret .= "\n";
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        $ret = [];
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            if ($propName === 'amount') {
                continue;
            }
            $ret[$propName] = $this->$propName;
        }

        return $ret;
    }

    /**
     * @return float
     */
    public function getNet()
    {
        return round($this->price / 1.23, 2);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }
}

