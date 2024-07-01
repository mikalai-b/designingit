<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Message extends Entity
{
	/**
	 * @access protected
	 * @var string
	 */
	protected $body;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $children;

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
	 * @var uuid
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Message
	 */
	protected $parent;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $receipts;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $sender;


	/**
	 * Instantiate a new Message
	 */
	public function __construct()
	{
		$this->children = new ArrayCollection();
		$this->receipts = new ArrayCollection();
	}


	/**
	 * Get the value of body
	 *
	 * @access public
	 * @return string The value of body
	 */
	public function getBody()
	{
		return $this->body;
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
	 * @return uuid The value of id
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of parent
	 *
	 * @access public
	 * @return Message The value of parent
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * Get the value of receipts
	 *
	 * @access public
	 * @return Collection The value of receipts
	 */
	public function getReceipts()
	{
		return $this->receipts;
	}


	/**
	 * Get the value of sender
	 *
	 * @access public
	 * @return Person The value of sender
	 */
	public function getSender()
	{
		return $this->sender;
	}


	/**
	 * Set the value of body
	 *
	 * @access public
	 * @param string $value The value to set to body
	 * @return Message The object instance for method chaining
	 */
	public function setBody($value)
	{
		$this->body = $value;

		return $this;
	}


	/**
	 * Set the value of children
	 *
	 * @access public
	 * @param Collection $value The value to set to children
	 * @return Message The object instance for method chaining
	 */
	public function setChildren(Collection $value = null)
	{
		$this->children = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Message The object instance for method chaining
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
	 * @return Message The object instance for method chaining
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
	 * @param uuid $value The value to set to id
	 * @return Message The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of parent
	 *
	 * @access public
	 * @param Message $value The value to set to parent
	 * @return Message The object instance for method chaining
	 */
	public function setParent(\Message $value = null)
	{
		$this->parent = $value;

		return $this;
	}


	/**
	 * Set the value of receipts
	 *
	 * @access public
	 * @param Collection $value The value to set to receipts
	 * @return Message The object instance for method chaining
	 */
	public function setReceipts(Collection $value = null)
	{
		$this->receipts = $value;

		return $this;
	}


	/**
	 * Set the value of sender
	 *
	 * @access public
	 * @param Person $value The value to set to sender
	 * @return Message The object instance for method chaining
	 */
	public function setSender(\Person $value = null)
	{
		$this->sender = $value;

		return $this;
	}
}
