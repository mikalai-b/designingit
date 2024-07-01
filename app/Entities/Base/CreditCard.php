<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class CreditCard extends Entity
{
	/**
	 * @access protected
	 * @var string
	 */
	protected $customer;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $name;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $orders;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $prescriptions;

	/**
	 * @access protected
	 * @var string
	 */
	protected $token;


	/**
	 * Instantiate a new CreditCard
	 */
	public function __construct()
	{
		$this->orders = new ArrayCollection();
		$this->prescriptions = new ArrayCollection();
	}


	/**
	 * Get the value of customer
	 *
	 * @access public
	 * @return string The value of customer
	 */
	public function getCustomer()
	{
		return $this->customer;
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
	 * Get the value of name
	 *
	 * @access public
	 * @return string The value of name
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Get the value of orders
	 *
	 * @access public
	 * @return Collection The value of orders
	 */
	public function getOrders()
	{
		return $this->orders;
	}


	/**
	 * Get the value of prescriptions
	 *
	 * @access public
	 * @return Collection The value of prescriptions
	 */
	public function getPrescriptions()
	{
		return $this->prescriptions;
	}


	/**
	 * Get the value of token
	 *
	 * @access public
	 * @return string The value of token
	 */
	public function getToken()
	{
		return $this->token;
	}


	/**
	 * Set the value of customer
	 *
	 * @access public
	 * @param string $value The value to set to customer
	 * @return CreditCard The object instance for method chaining
	 */
	public function setCustomer($value)
	{
		$this->customer = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return CreditCard The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of name
	 *
	 * @access public
	 * @param string $value The value to set to name
	 * @return CreditCard The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of orders
	 *
	 * @access public
	 * @param Collection $value The value to set to orders
	 * @return CreditCard The object instance for method chaining
	 */
	public function setOrders(Collection $value = null)
	{
		$this->orders = $value;

		return $this;
	}


	/**
	 * Set the value of prescriptions
	 *
	 * @access public
	 * @param Collection $value The value to set to prescriptions
	 * @return CreditCard The object instance for method chaining
	 */
	public function setPrescriptions(Collection $value = null)
	{
		$this->prescriptions = $value;

		return $this;
	}


	/**
	 * Set the value of token
	 *
	 * @access public
	 * @param string $value The value to set to token
	 * @return CreditCard The object instance for method chaining
	 */
	public function setToken($value)
	{
		$this->token = $value;

		return $this;
	}
}
