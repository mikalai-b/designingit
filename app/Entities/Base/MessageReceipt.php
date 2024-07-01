<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class MessageReceipt extends Entity
{
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
	 * @var \DateTime
	 */
	protected $dateSeen;

	/**
	 * @access protected
	 * @var uuid
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Message
	 */
	protected $message;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $recipient;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $review;


	/**
	 * Instantiate a new MessageReceipt
	 */
	public function __construct()
	{
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
	 * Get the value of dateSeen
	 *
	 * @access public
	 * @return \DateTime The value of dateSeen
	 */
	public function getDateSeen()
	{
		return $this->dateSeen;
	}


	/**
	 * Get the value of id
	 *
	 * @access public
	 * @return uuid The value of id
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of message
	 *
	 * @access public
	 * @return Message The value of message
	 */
	public function getMessage()
	{
		return $this->message;
	}


	/**
	 * Get the value of recipient
	 *
	 * @access public
	 * @return Person The value of recipient
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}


	/**
	 * Get the value of review
	 *
	 * @access public
	 * @return boolean The value of review
	 */
	public function getReview()
	{
		return $this->review;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return MessageReceipt The object instance for method chaining
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
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of dateSeen
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateSeen
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setDateSeen($value)
	{
		$this->dateSeen = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param uuid $value The value to set to id
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of message
	 *
	 * @access public
	 * @param Message $value The value to set to message
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setMessage(\Message $value = null)
	{
		$this->message = $value;

		return $this;
	}


	/**
	 * Set the value of recipient
	 *
	 * @access public
	 * @param Person $value The value to set to recipient
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setRecipient(\Person $value = null)
	{
		$this->recipient = $value;

		return $this;
	}


	/**
	 * Set the value of review
	 *
	 * @access public
	 * @param boolean $value The value to set to review
	 * @return MessageReceipt The object instance for method chaining
	 */
	public function setReview($value)
	{
		$this->review = $value;

		return $this;
	}
}
