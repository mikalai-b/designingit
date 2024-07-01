<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class PhotoType extends Entity
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
	 * @var string
	 */
	protected $description;

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
	 * @var integer
	 */
	protected $maxUploads;

	/**
	 * @access protected
	 * @var string
	 */
	protected $name;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $products;


	/**
	 * Instantiate a new PhotoType
	 */
	public function __construct()
	{
		$this->products = new ArrayCollection();
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
	 * Get the value of description
	 *
	 * @access public
	 * @return string The value of description
	 */
	public function getDescription()
	{
		return $this->description;
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
	 * Get the value of maxUploads
	 *
	 * @access public
	 * @return integer The value of maxUploads
	 */
	public function getMaxUploads()
	{
		return $this->maxUploads;
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
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return PhotoType The object instance for method chaining
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
	 * @return PhotoType The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of description
	 *
	 * @access public
	 * @param string $value The value to set to description
	 * @return PhotoType The object instance for method chaining
	 */
	public function setDescription($value)
	{
		$this->description = $value;

		return $this;
	}


	/**
	 * Set the value of displayOrder
	 *
	 * @access public
	 * @param integer $value The value to set to displayOrder
	 * @return PhotoType The object instance for method chaining
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
	 * @return PhotoType The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of maxUploads
	 *
	 * @access public
	 * @param integer $value The value to set to maxUploads
	 * @return PhotoType The object instance for method chaining
	 */
	public function setMaxUploads($value)
	{
		$this->maxUploads = $value;

		return $this;
	}


	/**
	 * Set the value of name
	 *
	 * @access public
	 * @param string $value The value to set to name
	 * @return PhotoType The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of products
	 *
	 * @access public
	 * @param Collection $value The value to set to products
	 * @return PhotoType The object instance for method chaining
	 */
	public function setProducts(Collection $value = null)
	{
		$this->products = $value;

		return $this;
	}
}
