<?php

class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $category;
    private $inCart;

    public function __construct($id, $name, $description, $price, $image, $category, $inCart = false) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->category = $category;
        $this->inCart = $inCart;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getFormattedPrice() {
        return '€' . number_format($this->price, 2, ',', '.');
    }

    public function getImage() {
        return $this->image;
    }

    public function getCategory() {
        return $this->category;
    }

    public function isInCart() {
        return $this->inCart;
    }

    public function setInCart($inCart) {
        $this->inCart = $inCart;
    }
}

