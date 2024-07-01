<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Question extends Entity
{
	/**
	 * @access protected
	 * @var boolean
	 */
	protected $active;

	/**
	 * @access protected
	 * @var json
	 */
	protected $config;

	/**
	 * @access protected
	 * @var string
	 */
	protected $content;

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
	protected $displayOrder;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $products;

	/**
	 * @access protected
	 * @var QuestionType
	 */
	protected $type;


	/**
	 * Instantiate a new Question
	 */
	public function __construct()
	{
		$this->products = new ArrayCollection();
	}


	/**
	 * Get the value of active
	 *
	 * @access public
	 * @return boolean The value of active
	 */
	public function getActive()
	{
		return $this->active;
	}


	/**
	 * Get the value of config
	 *
	 * @access public
	 * @return json The value of config
	 */
	public function getConfig()
	{
		return $this->config;
	}


	/**
	 * Get the value of content
	 *
	 * @access public
	 * @return string The value of content
	 */
	public function getContent()
	{
		return $this->content;
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
	 * Get the value of displayOrder
	 *
	 * @access public
	 * @return integer The value of displayOrder
	 */
	public function getDisplayOrder()
	{
		return $this->displayOrder;
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
	 * Get the value of products
	 *
	 * @access public
	 * @return Collection The value of products
	 */
	public function getProducts()
	{
		return $this->products;
	}


	/**
	 * Get the value of type
	 *
	 * @access public
	 * @return QuestionType The value of type
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * Set the value of active
	 *
	 * @access public
	 * @param boolean $value The value to set to active
	 * @return Question The object instance for method chaining
	 */
	public function setActive($value)
	{
		$this->active = $value;

		return $this;
	}


	/**
	 * Set the value of config
	 *
	 * @access public
	 * @param json $value The value to set to config
	 * @return Question The object instance for method chaining
	 */
	public function setConfig($value)
	{
		$this->config = $value;

		return $this;
	}


	/**
	 * Set the value of content
	 *
	 * @access public
	 * @param string $value The value to set to content
	 * @return Question The object instance for method chaining
	 */
	public function setContent($value)
	{
		$this->content = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Question The object instance for method chaining
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
	 * @return Question The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of displayOrder
	 *
	 * @access public
	 * @param integer $value The value to set to displayOrder
	 * @return Question The object instance for method chaining
	 */
	public function setDisplayOrder($value)
	{
		$this->displayOrder = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Question The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of products
	 *
	 * @access public
	 * @param Collection $value The value to set to products
	 * @return Question The object instance for method chaining
	 */
	public function setProducts(Collection $value = null)
	{
		$this->products = $value;

		return $this;
	}


	/**
	 * Set the value of type
	 *
	 * @access public
	 * @param QuestionType $value The value to set to type
	 * @return Question The object instance for method chaining
	 */
	public function setType(\QuestionType $value = null)
	{
		$this->type = $value;

		return $this;
	}
}
