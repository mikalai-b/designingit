<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Offer extends Entity
{
	/**
	 * @access protected
	 * @var Campaign
	 */
	protected $campaign;

	/**
	 * @access protected
	 * @var float
	 */
	protected $firstShipmentPrice;

    /**
     * @access protected
     * @var float
     */
    protected $secondShipmentPrice;

    /**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var float
	 */
	protected $price;

	/**
	 * @access protected
	 * @var Product
	 */
	protected $product;

	/**
	 * @access protected
	 * @var string
	 */
	protected $successMessage;


	/**
	 * Instantiate a new Offer
	 */
	public function __construct()
	{
	}


	/**
	 * Get the value of campaign
	 *
	 * @access public
	 * @return Campaign The value of campaign
	 */
	public function getCampaign()
	{
		return $this->campaign;
	}


	/**
	 * Get the value of firstShipmentPrice
	 *
	 * @access public
	 * @return float The value of firstShipmentPrice
	 */
	public function getFirstShipmentPrice()
	{
		return $this->firstShipmentPrice;
	}

    /**
     * Get the value of firstShipmentPrice
     *
     * @access public
     * @return float The value of firstShipmentPrice
     */
    public function getSecondShipmentPrice()
    {
        return $this->secondShipmentPrice;
    }

	/**
	 * Get the value of id
	 *
	 * @access public
	 * @return integer The value of id
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of price
	 *
	 * @access public
	 * @return float The value of price
	 */
	public function getPrice()
	{
		return $this->price;
	}


	/**
	 * Get the value of product
	 *
	 * @access public
	 * @return Product The value of product
	 */
	public function getProduct()
	{
		return $this->product;
	}


	/**
	 * Get the value of successMessage
	 *
	 * @access public
	 * @return string The value of successMessage
	 */
	public function getSuccessMessage()
	{
		return $this->successMessage;
	}


	/**
	 * Set the value of campaign
	 *
	 * @access public
	 * @param Campaign $value The value to set to campaign
	 * @return Offer The object instance for method chaining
	 */
	public function setCampaign(\Campaign $value = null)
	{
		$this->campaign = $value;

		return $this;
	}


	/**
	 * Set the value of firstShipmentPrice
	 *
	 * @access public
	 * @param float $value The value to set to firstShipmentPrice
	 * @return Offer The object instance for method chaining
	 */
	public function setFirstShipmentPrice($value)
	{
		$this->firstShipmentPrice = $value;

		return $this;
	}

    /**
     * Set the value of secondShipmentPrice
     *
     * @access public
     * @param float $value The value to set to secondShipmentPrice
     * @return Offer The object instance for method chaining
     */
    public function setSecondShipmentPrice($value)
    {
        $this->secondShipmentPrice = $value;

        return $this;
    }

    /**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Offer The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of price
	 *
	 * @access public
	 * @param float $value The value to set to price
	 * @return Offer The object instance for method chaining
	 */
	public function setPrice($value)
	{
		$this->price = $value;

		return $this;
	}


	/**
	 * Set the value of product
	 *
	 * @access public
	 * @param Product $value The value to set to product
	 * @return Offer The object instance for method chaining
	 */
	public function setProduct(\Product $value = null)
	{
		$this->product = $value;

		return $this;
	}


	/**
	 * Set the value of successMessage
	 *
	 * @access public
	 * @param string $value The value to set to successMessage
	 * @return Offer The object instance for method chaining
	 */
	public function setSuccessMessage($value)
	{
		$this->successMessage = $value;

		return $this;
	}
}
