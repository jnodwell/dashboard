<?php

/**
 * IndexController.php
 *
 * @category Nodwell
 * @package Nodwell_CustomAccount
 * @copyright Copyright (c) 2017 nodwell.net (www.nodwell.net)
 * @author jennifer@nodwell.net
 */
class Nodwell_CustomAccount_IndexController extends Mage_Core_Controller_Front_Action {
    /**
     * function preDispatch
     *
     */
    public function preDispatch() {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
        $loginUrl = Mage::helper('customer')->getLoginUrl();

        if (!Mage::getSingleton('customer/session')->authenticate($this, $loginUrl)) {
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * function indexAction
     *
     */
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * function requestAction
     *
     */
    public function requestAction() {
        $data = $this->getRequest()->getPost();
        if ($data['low-price'] == '' || $data['high-price'] == '') {
            $response = $this->__('You must fill in the required fields.');
        } elseif ($data['low-price'] < 0 || $data['low-price'] > $data['high-price'] || $data['high-price'] > 5 * $data['low-price']) {
            $response = $this->__('Please enter valid price data: any value greater than "Low Range" and no more than 5x higher than the entered "Low Range".');
        } else {
            $products = Mage::getModel('catalog/product')->getCollection();
            $products->addAttributeToFilter('price', array('lt' => $data['high-price']));
            $products->addAttributeToFilter('price', array('gt' => $data['low-price']));
            $products->addAttributeToSort('price', $data['sort']);
            $products->addAttributeToFilter('status', 1); // enabled
            $products->addAttributeToSelect('*');
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($products);
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
            $products->setPageSize(10);
            if (count($products) == 0) {
                $response = $this->__("There are no product matching with range!");
            } else {
                $block = $this->getLayout()->createBlock('customaccount/products')->setTemplate('customaccount/products.phtml');
                $block->setProducts($products);
                $response = $block->toHtml();
            }
        }
        //$this->getResponse()->setBody($response);
        //echo $response;
    }
}