<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Symptom extends Entity
{
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
	 * @var string
	 */
	protected $id;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $productTypes;


	/**
	 * Instantiate a new Symptom
	 */
	public function __construct()
	{
		$this->productTypes = new ArrayCollection();
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
	 * Get the value of id
	 *
	 * @access public
	 * @return string The value of id
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of productTypes
	 *
	 * @access public
	 * @return Collection The value of productTypes
	 */
	public function getProductTypes()
	{
		return $this->productTypes;
	}


	/**
	 * Set the value of content
	 *
	 * @access public
	 * @param string $value The value to set to content
	 * @return Symptom The object instance for method chaining
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
	 * @return Symptom The object instance for method chaining
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
	 * @return Symptom The object instance for method chaining
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
	 * @param string $value The value to set to id
	 * @return Symptom The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of productTypes
	 *
	 * @access public
	 * @param Collection $value The value to set to productTypes
	 * @return Symptom The object instance for method chaining
	 */
	public function setProductTypes(Collection $value = null)
	{
		$this->productTypes = $value;

		return $this;
	}
}
