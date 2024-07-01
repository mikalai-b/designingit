<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Prescription extends Entity
{
	/**
	 * @access protected
	 * @var Consultation
	 */
	protected $consultation;

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
	protected $dateEnd;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateLastRefilled;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateModified;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateStart;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $filled;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $instructions;

	/**
	 * @access protected
	 * @var LineItem
	 */
	protected $lineItem;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $refills;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $resupplyAttempts;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $sentReorderNotification;

	/**
	 * @access protected
	 * @var string
	 */
	protected $status;


	/**
	 * Instantiate a new Prescription
	 */
	public function __construct()
	{
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
	 * Get the value of dateEnd
	 *
	 * @access public
	 * @return \DateTime The value of dateEnd
	 */
	public function getDateEnd()
	{
		return $this->dateEnd;
	}


	/**
	 * Get the value of dateLastRefilled
	 *
	 * @access public
	 * @return \DateTime The value of dateLastRefilled
	 */
	public function getDateLastRefilled()
	{
		return $this->dateLastRefilled;
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
	 * Get the value of dateStart
	 *
	 * @access public
	 * @return \DateTime The value of dateStart
	 */
	public function getDateStart()
	{
		return $this->dateStart;
	}


	/**
	 * Get the value of filled
	 *
	 * @access public
	 * @return integer The value of filled
	 */
	public function getFilled()
	{
		return $this->filled;
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
	 * Get the value of instructions
	 *
	 * @access public
	 * @return string The value of instructions
	 */
	public function getInstructions()
	{
		return $this->instructions;
	}


	/**
	 * Get the value of lineItem
	 *
	 * @access public
	 * @return LineItem The value of lineItem
	 */
	public function getLineItem()
	{
		return $this->lineItem;
	}


	/**
	 * Get the value of refills
	 *
	 * @access public
	 * @return integer The value of refills
	 */
	public function getRefills()
	{
		return $this->refills;
	}


	/**
	 * Get the value of resupplyAttempts
	 *
	 * @access public
	 * @return integer The value of resupplyAttempts
	 */
	public function getResupplyAttempts()
	{
		return $this->resupplyAttempts;
	}


	/**
	 * Get the value of sentReorderNotification
	 *
	 * @access public
	 * @return boolean The value of sentReorderNotification
	 */
	public function getSentReorderNotification()
	{
		return $this->sentReorderNotification;
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
	 * Set the value of consultation
	 *
	 * @access public
	 * @param Consultation $value The value to set to consultation
	 * @return Prescription The object instance for method chaining
	 */
	public function setConsultation(\Consultation $value = null)
	{
		$this->consultation = $value;

		return $this;
	}


	/**
	 * Set the value of creditCard
	 *
	 * @access public
	 * @param CreditCard $value The value to set to creditCard
	 * @return Prescription The object instance for method chaining
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
	 * @return Prescription The object instance for method chaining
	 */
	public function setDateCreated($value)
	{
		$this->dateCreated = $value;

		return $this;
	}


	/**
	 * Set the value of dateEnd
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateEnd
	 * @return Prescription The object instance for method chaining
	 */
	public function setDateEnd($value)
	{
		$this->dateEnd = $value;

		return $this;
	}


	/**
	 * Set the value of dateLastRefilled
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateLastRefilled
	 * @return Prescription The object instance for method chaining
	 */
	public function setDateLastRefilled($value)
	{
		$this->dateLastRefilled = $value;

		return $this;
	}


	/**
	 * Set the value of dateModified
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateModified
	 * @return Prescription The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of dateStart
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateStart
	 * @return Prescription The object instance for method chaining
	 */
	public function setDateStart($value)
	{
		$this->dateStart = $value;

		return $this;
	}


	/**
	 * Set the value of filled
	 *
	 * @access public
	 * @param integer $value The value to set to filled
	 * @return Prescription The object instance for method chaining
	 */
	public function setFilled($value)
	{
		$this->filled = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Prescription The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of instructions
	 *
	 * @access public
	 * @param string $value The value to set to instructions
	 * @return Prescription The object instance for method chaining
	 */
	public function setInstructions($value)
	{
		$this->instructions = $value;

		return $this;
	}


	/**
	 * Set the value of lineItem
	 *
	 * @access public
	 * @param LineItem $value The value to set to lineItem
	 * @return Prescription The object instance for method chaining
	 */
	public function setLineItem(\LineItem $value = null)
	{
		$this->lineItem = $value;

		return $this;
	}


	/**
	 * Set the value of refills
	 *
	 * @access public
	 * @param integer $value The value to set to refills
	 * @return Prescription The object instance for method chaining
	 */
	public function setRefills($value)
	{
		$this->refills = $value;

		return $this;
	}


	/**
	 * Set the value of resupplyAttempts
	 *
	 * @access public
	 * @param integer $value The value to set to resupplyAttempts
	 * @return Prescription The object instance for method chaining
	 */
	public function setResupplyAttempts($value)
	{
		$this->resupplyAttempts = $value;

		return $this;
	}


	/**
	 * Set the value of sentReorderNotification
	 *
	 * @access public
	 * @param boolean $value The value to set to sentReorderNotification
	 * @return Prescription The object instance for method chaining
	 */
	public function setSentReorderNotification($value)
	{
		$this->sentReorderNotification = $value;

		return $this;
	}


	/**
	 * Set the value of status
	 *
	 * @access public
	 * @param string $value The value to set to status
	 * @return Prescription The object instance for method chaining
	 */
	public function setStatus($value)
	{
		$this->status = $value;

		return $this;
	}
}
