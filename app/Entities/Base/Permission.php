<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Permission extends Entity
{
	/**
	 * @access protected
	 * @var string
	 */
	protected $description;

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
	 * Instantiate a new Permission
	 */
	public function __construct()
	{
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
	 * Set the value of description
	 *
	 * @access public
	 * @param string $value The value to set to description
	 * @return Permission The object instance for method chaining
	 */
	public function setDescription($value)
	{
		$this->description = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Permission The object instance for method chaining
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
	 * @return Permission The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}
}
