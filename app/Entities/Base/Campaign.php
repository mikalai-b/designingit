<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Campaign extends Entity
{
	/**
	 * @access protected
	 * @var Collection
	 */
	protected $couponCodes;

	/**
	 * @access protected
	 * @var json
	 */
	protected $effects;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $endDate;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $offers;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * @access protected
	 * @var string
	 */
	protected $successMessage;

	/**
	 * @access protected
	 * @var string
	 */
	protected $title;


	/**
	 * Instantiate a new Campaign
	 */
	public function __construct()
	{
		$this->offers = new ArrayCollection();
		$this->couponCodes = new ArrayCollection();
	}


	/**
	 * Get the value of couponCodes
	 *
	 * @access public
	 * @return Collection The value of couponCodes
	 */
	public function getCouponCodes()
	{
		return $this->couponCodes;
	}


	/**
	 * Get the value of effects
	 *
	 * @access public
	 * @return json The value of effects
	 */
	public function getEffects()
	{
		return $this->effects;
	}


	/**
	 * Get the value of endDate
	 *
	 * @access public
	 * @return \DateTime The value of endDate
	 */
	public function getEndDate()
	{
		return $this->endDate;
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
	 * Get the value of offers
	 *
	 * @access public
	 * @return Collection The value of offers
	 */
	public function getOffers()
	{
		return $this->offers;
	}


	/**
	 * Get the value of startDate
	 *
	 * @access public
	 * @return \DateTime The value of startDate
	 */
	public function getStartDate()
	{
		return $this->startDate;
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
	 * Get the value of title
	 *
	 * @access public
	 * @return string The value of title
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * Set the value of couponCodes
	 *
	 * @access public
	 * @param Collection $value The value to set to couponCodes
	 * @return Campaign The object instance for method chaining
	 */
	public function setCouponCodes(Collection $value = null)
	{
		$this->couponCodes = $value;

		return $this;
	}


	/**
	 * Set the value of effects
	 *
	 * @access public
	 * @param json $value The value to set to effects
	 * @return Campaign The object instance for method chaining
	 */
	public function setEffects($value)
	{
		$this->effects = $value;

		return $this;
	}


	/**
	 * Set the value of endDate
	 *
	 * @access public
	 * @param \DateTime $value The value to set to endDate
	 * @return Campaign The object instance for method chaining
	 */
	public function setEndDate($value)
	{
		$this->endDate = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Campaign The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of offers
	 *
	 * @access public
	 * @param Collection $value The value to set to offers
	 * @return Campaign The object instance for method chaining
	 */
	public function setOffers(Collection $value = null)
	{
		$this->offers = $value;

		return $this;
	}


	/**
	 * Set the value of startDate
	 *
	 * @access public
	 * @param \DateTime $value The value to set to startDate
	 * @return Campaign The object instance for method chaining
	 */
	public function setStartDate($value)
	{
		$this->startDate = $value;

		return $this;
	}


	/**
	 * Set the value of successMessage
	 *
	 * @access public
	 * @param string $value The value to set to successMessage
	 * @return Campaign The object instance for method chaining
	 */
	public function setSuccessMessage($value)
	{
		$this->successMessage = $value;

		return $this;
	}


	/**
	 * Set the value of title
	 *
	 * @access public
	 * @param string $value The value to set to title
	 * @return Campaign The object instance for method chaining
	 */
	public function setTitle($value)
	{
		$this->title = $value;

		return $this;
	}
}
