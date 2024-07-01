<?php

use Illuminate\Support\Collection;

class Campaign extends Base\Campaign
{
	const EFFECT_TYPE_PERCENT_DISCOUNT = 'percent_discount';
	const EFFECT_TYPE_VALUE_DISCOUNT = 'value_discount';
	const EFFECT_TYPE_ARBITRARY_PRICING = 'arbitrary_pricing';

	const PRICING_EFFECT_TYPES = [
		'percent_discount',
		'value_discount',
		'arbitrary_pricing',
	];

	const EFFECT_CONTEXT_ALL_SHIPMENTS = 'all_shipments';
	const EFFECT_CONTEXT_FIRST_SHIPMENT = 'first_shipment';
    const EFFECT_CONTEXT_SECOND_SHIPMENT = 'second_shipment';

	/**
	 * Instantiate a new Campaign
	 */
	public function __construct()
	{
		return parent::__construct();
	}

	/**
	 *
	 * @param Product $product
	 * @return boolean
	 */
	public function includesProduct(Product $product)
	{
		return !empty ($this->getOfferForProduct($product));
	}

	/**
	 *
	 * @param Product $product
	 * @return Offer | null
	 */
	public function getOfferForProduct(Product $product)
	{
		return collect($this->getOffers())
			->filter(function (Offer $offer) use ($product) {
				return $offer->getProduct() === $product;
			})
			->first();
	}

	/**
	 *
	 * @param Product $product
	 * @return decimal|void
	 */
	public function getPriceForProduct(Product $product)
	{
		$price = $this->getOfferedPriceForProduct($product);
		if (!is_null($price)) {
			return $price;
		}
		$price = $this->getAffectedPriceForProduct($product);
		if (!is_null($price)) {
			return $price;
		}
		return $product->getPrice();
	}

	public function getSuccessMessageForProduct(Product $product)
	{
		$offer = $this->getOfferForProduct($product);
		if ($offer) {
			return $offer->getSuccessMessage() ?: $this->getSuccessMessage();
		}
		return $this->getSuccessMessage();
	}

	/**
	 *
	 * @param Product $product
	 * @return decimal|void
	 */
	public function getFirstShipmentPriceForProduct(Product $product)
	{
		$price = $this->getOfferedFirstShipmentPriceForProduct($product);
		if (!is_null($price)) {
			return $price;
		}
		$price = $this->getAffectedFirstShipmentPriceForProduct($product);
		if (!is_null($price)) {
			return $price;
		}
		$price = $this->getOfferedPriceForProduct($product);
		if (!is_null($price)) {
			return $price;
		}
		return $product->getPrice();
	}

    public function getSecondShipmentPriceForProduct(Product $product)
    {
        $price = $this->getOfferedSecondShipmentPriceForProduct($product);
        if (!is_null($price)) {
            return $price;
        }
        $price = $this->getAffectedSecondShipmentPriceForProduct($product);
        if (!is_null($price)) {
            return $price;
        }
        $price = $this->getOfferedPriceForProduct($product);
        if (!is_null($price)) {
            return $price;
        }
        return $product->getPrice();
    }

    /**
	 * Returns a special price for a product from its offer record.
	 *
	 * @param Product $product
	 * @return decimal | null
	 */
	public function getOfferedPriceForProduct(Product $product)
	{
		$offer = $this->getOfferForProduct($product);
		if ($offer) {
			return $offer->getPrice();
		}
	}

	public function getAffectedPriceForProduct(Product $product)
	{
		$basePrice = $product->getPrice();
		$effects = collect($this->getEffects())
			->whereIn('type', static::PRICING_EFFECT_TYPES)
			->where('context', static::EFFECT_CONTEXT_ALL_SHIPMENTS);

		return $this->adjustPriceFromEffects($effects, $basePrice);
	}

	public function getAffectedFirstShipmentPriceForProduct(Product $product)
	{
		$basePrice = $product->getPrice();
		$effects = collect($this->getEffects())
			->whereIn('type', static::PRICING_EFFECT_TYPES)
			->where('context', static::EFFECT_CONTEXT_FIRST_SHIPMENT);
		if (!count($effects)) {
			$effects = collect($this->getEffects())
				->where('context', static::EFFECT_CONTEXT_ALL_SHIPMENTS);
		}
		return $this->adjustPriceFromEffects($effects, $basePrice);
	}

    public function getAffectedSecondShipmentPriceForProduct(Product $product)
    {
        $basePrice = $product->getPrice();
        $effects = collect($this->getEffects())
            ->whereIn('type', static::PRICING_EFFECT_TYPES)
            ->where('context', static::EFFECT_CONTEXT_SECOND_SHIPMENT);
        if (!count($effects)) {
            $effects = collect($this->getEffects())
                ->where('context', static::EFFECT_CONTEXT_ALL_SHIPMENTS);
        }
        return $this->adjustPriceFromEffects($effects, $basePrice);
    }

    /** @test */
	public function adjustPriceFromEffects(Collection $effects, $price)
	{
		if ($arbitraryPricingEffect = $effects->firstWhere('type', static::EFFECT_TYPE_ARBITRARY_PRICING)) {
			return ($arbitraryPricingEffect['value'] ?? null) !== null ? $arbitraryPricingEffect['value'] : $price;
		}
		if ($percentPricingEffect = $effects->firstWhere('type', static::EFFECT_TYPE_PERCENT_DISCOUNT)) {
			return round($price - ($price * ($percentPricingEffect['value'] / 100)), 2);
		}
		if ($valuePricingEffect = $effects->firstWhere('type', static::EFFECT_TYPE_VALUE_DISCOUNT)) {
			return $price - $valuePricingEffect['value'];
		}
		return $price;
	}


	/**
	 * Returns a special first shipment price for a product from its
	 * offer record.
	 *
	 * @param Product $product
	 * @return decimal | null
	 */
	public function getOfferedFirstShipmentPriceForProduct(Product $product)
	{
		$offer = $this->getOfferForProduct($product);
		if ($offer) {
			return $offer->getFirstShipmentPrice();
		}
	}

    /**
     * Returns a special first shipment price for a product from its
     * offer record.
     *
     * @param Product $product
     * @return decimal | null
     */
    public function getOfferedSecondShipmentPriceForProduct(Product $product)
    {
        $offer = $this->getOfferForProduct($product);
        if ($offer) {
            return $offer->getSecondShipmentPrice();
        }
    }

    /**
	 *
	 * @return Base\json
	 */
	public function getEffects()
	{
		return parent::getEffects() ?: '[]';
	}

	/**
	 * @return boolean
	 */
	public function isExpired()
	{
		return $this->getEndDate() && $this->getEndDate()->format('Y-m-d') < date('Y-m-d');
	}

	/**
	 * @return boolean
	 */
	public function isNotYetActive()
	{
		return $this->getStartDate() && $this->getStartDate()->format('Y-m-d') > date('Y-m-d');
	}
}
