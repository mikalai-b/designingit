<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Order extends Entity
{
	/**
	 * @access protected
	 * @var Collection
	 */
	protected $children;

	/**
	 * @access protected
	 * @var Consultation
	 */
	protected $consultation;

	/**
	 * @access protected
	 * @var string
	 */
	protected $couponCode;

	/**
	 * @access protected
	 * @var CreditCard
	 */
	protected $creditCard;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateCreated;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateModified;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $lineItems;

	/**
	 * @access protected
	 * @var Order
	 */
	protected $parent;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $person;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $provider;

	/**
	 * @access protected
	 * @var State
	 */
	protected $state;

	/**
	 * @access protected
	 * @var string
	 */
	protected $status;


	/**
	 * Instantiate a new Order
	 */
	public function __construct()
	{
		$this->lineItems = new ArrayCollection();
		$this->children = new ArrayCollection();
	}


	/**
	 * Get the value of children
	 *
	 * @access public
	 * @return Collection The value of children
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * Get the value of consultation
	 *
	 * @access public
	 * @return Consultation The value of consultation
	 */
	public function getConsultation()
	{
		return $this->consultation;
	}


	/**
	 * Get the value of couponCode
	 *
	 * @access public
	 * @return string The value of couponCode
	 */
	public function getCouponCode()
	{
		return $this->couponCode;
	}


	/**
	 * Get the value of creditCard
	 *
	 * @access public
	 * @return CreditCard The value of creditCard
	 */
	public function getCreditCard()
	{
		return $this->creditCard;
	}


	/**
	 * Get the value of dateCreated
	 *
	 * @access public
	 * @return \DateTime The value of dateCreated
	 */
	public function getDateCreated()
	{
		return $this->dateCreated;
	}


	/**
	 * Get the value of dateModified
	 *
	 * @access public
	 * @return \DateTime The value of dateModified
	 */
	public function getDateModified()
	{
		return $this->dateModified;
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
	 * Get the value of lineItems
	 *
	 * @access public
	 * @return Collection The value of lineItems
	 */
	public function getLineItems()
	{
		return $this->lineItems;
	}


	/**
	 * Get the value of parent
	 *
	 * @access public
	 * @return Order The value of parent
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * Get the value of person
	 *
	 * @access public
	 * @return Person The value of person
	 */
	public function getPerson()
	{
		return $this->person;
	}


	/**
	 * Get the value of provider
	 *
	 * @access public
	 * @return Person The value of provider
	 */
	public function getProvider()
	{
		return $this->provider;
	}


	/**
	 * Get the value of state
	 *
	 * @access public
	 * @return State The value of state
	 */
	public function getState()
	{
		return $this->state;
	}


	/**
	 * Get the value of status
	 *
	 * @access public
	 * @return string The value of status
	 */
	public function getStatus()
	{
		return $this->status;
	}


	/**
	 * Set the value of children
	 *
	 * @access public
	 * @param Collection $value The value to set to children
	 * @return Order The object instance for method chaining
	 */
	public function setChildren(Collection $value = null)
	{
		$this->children = $value;

		return $this;
	}


	/**
	 * Set the value of consultation
	 *
	 * @access public
	 * @param Consultation $value The value to set to consultation
	 * @return Order The object instance for method chaining
	 */
	public function setConsultation(\Consultation $value = null)
	{
		$this->consultation = $value;

		return $this;
	}


	/**
	 * Set the value of couponCode
	 *
	 * @access public
	 * @param string $value The value to set to couponCode
	 * @return Order The object instance for method chaining
	 */
	public function setCouponCode($value)
	{
		$this->couponCode = $value;

		return $this;
	}


	/**
	 * Set the value of creditCard
	 *
	 * @access public
	 * @param CreditCard $value The value to set to creditCard
	 * @return Order The object instance for method chaining
	 */
	public function setCreditCard(\CreditCard $value = null)
	{
		$this->creditCard = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Order The object instance for method chaining
	 */
	public function setDateCreated($value)
	{
		$this->dateCreated = $value;

		return $this;
	}


	/**
	 * Set the value of dateModified
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateModified
	 * @return Order The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Order The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of lineItems
	 *
	 * @access public
	 * @param Collection $value The value to set to lineItems
	 * @return Order The object instance for method chaining
	 */
	public function setLineItems(Collection $value = null)
	{
		$this->lineItems = $value;

		return $this;
	}


	/**
	 * Set the value of parent
	 *
	 * @access public
	 * @param Order $value The value to set to parent
	 * @return Order The object instance for method chaining
	 */
	public function setParent(\Order $value = null)
	{
		$this->parent = $value;

		return $this;
	}


	/**
	 * Set the value of person
	 *
	 * @access public
	 * @param Person $value The value to set to person
	 * @return Order The object instance for method chaining
	 */
	public function setPerson(\Person $value = null)
	{
		$this->person = $value;

		return $this;
	}


	/**
	 * Set the value of provider
	 *
	 * @access public
	 * @param Person $value The value to set to provider
	 * @return Order The object instance for method chaining
	 */
	public function setProvider(\Person $value = null)
	{
		$this->provider = $value;

		return $this;
	}


	/**
	 * Set the value of state
	 *
	 * @access public
	 * @param State $value The value to set to state
	 * @return Order The object instance for method chaining
	 */
	public function setState(\State $value = null)
	{
		$this->state = $value;

		return $this;
	}


	/**
	 * Set the value of status
	 *
	 * @access public
	 * @param string $value The value to set to status
	 * @return Order The object instance for method chaining
	 */
	public function setStatus($value)
	{
		$this->status = $value;

		return $this;
	}
}
