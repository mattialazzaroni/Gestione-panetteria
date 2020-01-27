<?php
/**
 * Created by PhpStorm.
 * User: mattia.ruberto01
 * Date: 15.05.2019
 * Time: 09:11
 */
require 'ShoppingCart.php';
require 'Product.php';
use PHPUnit\Framework\TestCase;

class TestShoppingCart extends TestCase
{
    private $shoppingCart;
    private $item;

    public function setUp(){
        $this->shoppingCart = new ShoppingCart();
        $this->item = new Product("Pesce", 10);
    }

    public function testEmpty(){
        $this->assertEmpty($this->shoppingCart->items);
    }

    /**
     * @depends testEmpty
     */
    public function testAdditem(){
        $this->shoppingCart->addItem($this->item);
        $this->assertNotEmpty($this->shoppingCart->items);
    }

    /**
     * @depends testAdditem
     */
    public function testRemoveItem(){
        $this->shoppingCart->removeItem($this->item);
        $this->assertEmpty($this->shoppingCart->items);
    }

    /**
     * @depends testRemoveitem
     * @expectedException InvalidArgumentException
     */
    public function testRemoveItemNotInCart(){
        $this->shoppingCart->removeItem($this->item);
    }
}