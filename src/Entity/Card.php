<?php

namespace App\Entity;

class Card {
    protected string $color;

    protected string $value;
    protected int $indexColor;
    protected int $indexValue;

    public function __construct(string $color, string $value)
    {
        $this->color = $color;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function _toString()
    {
        return $this->value.' '.$this->color;
    }

    /**
     * @return int
     */
    public function getIndexColor(): int
    {
        return $this->indexColor;
    }

    /**
     * @param int $indexColor
     */
    public function setIndexColor(int $indexColor): void
    {
        $this->indexColor = $indexColor;
    }

    /**
     * @return int
     */
    public function getIndexValue(): int
    {
        return $this->indexValue;
    }

    /**
     * @param int $indexValue
     */
    public function setIndexValue(int $indexValue): void
    {
        $this->indexValue = $indexValue;
    }
}