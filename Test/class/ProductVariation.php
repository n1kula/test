<?php


class ProductVariation extends Product
{
    /**
     * @var string
     */
    protected $color;

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
