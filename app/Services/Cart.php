<?php

namespace App\Services;

use Campaign;
use Checkout;
use CouponCode;
use Gloudemans\Shoppingcart\Cart as BaseCart;
use Illuminate\Session\SessionManager;
use Illuminate\Contracts\Events\Dispatcher;
use Order;
use Product;
use Products;

class Cart extends BaseCart
{
    protected $order;
    protected $couponCode;
    protected $checkout;
    protected $campaign;
    protected $productRepository;

    public function __construct(SessionManager $session, Dispatcher $events)
    {
        parent::__construct($session, $events);
        $this->initialize();
        $this->productRepository = app()->make(Products::class);
    }

    public function initialize()
    {
        $this->checkout = app()->make(Checkout::class);
    }

    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * @return null|Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return null|CouponCode
     */
    public function getCouponCode()
    {
        return $this->checkout ? $this->checkout->getCouponCodeObject() : null;
    }

    /**
     * @return null|Campaign
     */
    public function getCampaign()
    {
        if ($this->getCouponCode()) {
            return $this->getCouponCode()->getCampaign();
        }
        return null;
    }

    public function getPriceForProduct(Product $product)
    {
        if ($campaign = $this->getCampaign()) {
            return $campaign->getPriceForProduct($product);
        }
        return collect($this->content())
            ->where('id', (string) $product->getId())
            ->first()
            ->price;
    }

    public function getProductCategory(Product $product)
    {
        return $product->getCategory();
    }

    public function getFirstShipmentPriceForProduct(Product $product)
    {
        if ($campaign = $this->getCampaign()) {
            return $campaign->getFirstShipmentPriceForProduct($product);
        }
        return collect($this->content())
            ->where('id', (string) $product->getId())
            ->first()
            ->price;
    }

    public function getSecondShipmentPriceForProduct(Product $product)
    {
        if ($campaign = $this->getCampaign()) {
            return $campaign->getSecondShipmentPriceForProduct($product);
        }
        return collect($this->content())
            ->where('id', (string) $product->getId())
            ->first()
            ->price;
    }
}