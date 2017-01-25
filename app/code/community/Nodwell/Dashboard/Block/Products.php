<?php

/**
 * Products.php
 *
 * @category Nodwell
 * @package Nodwell_CustomAccount
 * @copyright Copyright (c) 2017 nodwell.net (www.nodwell.net)
 * @author jennifer@nodwell.net
 */
class Nodwell_CustomAccount_Block_Products extends Mage_Core_Block_Template {
    /**
     * @var
     */
    protected $_products;

    /**
     * function setProducts
     *
     * @param $products
     */
    public function setProducts($products) {
        $this->_products = $products;
    }

    /**
     * function getProducts
     *
     * @return mixed
     */
    public function getProducts() {
        return $this->_products;
    }
}