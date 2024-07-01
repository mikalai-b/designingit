<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class LineItem extends Entity
{
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
	 * @var Order
	 */
	protected $order;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $period;

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
	 * Instantiate a new LineItem
	 */
	public function __construct()
	{
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
	 * Get the value of order
	 *
	 * @access public
	 * @return Order The value of order
	 */
	public function getOrder()
	{
		return $this->order;
	}


	/**
	 * Get the value of period
	 *
	 * @access public
	 * @return integer The value of period
	 */
	public function getPeriod()
	{
		return $this->period;
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
	 * Set the value of firstShipmentPrice
	 *
	 * @access public
	 * @param float $value The value to set to firstShipmentPrice
	 * @return LineItem The object instance for method chaining
	 */
	public function setFirstShipmentPrice($value)
	{
		$this->firstShipmentPrice = $value;

		return $this;
	}

    /**
     * Set the value of firstShipmentPrice
     *
     * @access public
     * @param float $value The value to set to firstShipmentPrice
     * @return LineItem The object instance for method chaining
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
	 * @return LineItem The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of order
	 *
	 * @access public
	 * @param Order $value The value to set to order
	 * @return LineItem The object instance for method chaining
	 */
	public function setOrder(\Order $value = null)
	{
		$this->order = $value;

		return $this;
	}


	/**
	 * Set the value of period
	 *
	 * @access public
	 * @param integer $value The value to set to period
	 * @return LineItem The object instance for method chaining
	 */
	public function setPeriod($value)
	{
		$this->period = $value;

		return $this;
	}


	/**
	 * Set the value of price
	 *
	 * @access public
	 * @param float $value The value to set to price
	 * @return LineItem The object instance for method chaining
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
	 * @return LineItem The object instance for method chaining
	 */
	public function setProduct(\Product $value = null)
	{
		$this->product = $value;

		return $this;
	}
}
