<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Role extends Entity
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
	 * @access protected
	 * @var Collection
	 */
	protected $permissions;


	/**
	 * Instantiate a new Role
	 */
	public function __construct()
	{
		$this->permissions = new ArrayCollection();
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
	 * Get the value of permissions
	 *
	 * @access public
	 * @return Collection The value of permissions
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}


	/**
	 * Set the value of description
	 *
	 * @access public
	 * @param string $value The value to set to description
	 * @return Role The object instance for method chaining
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
	 * @return Role The object instance for method chaining
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
	 * @return Role The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of permissions
	 *
	 * @access public
	 * @param Collection $value The value to set to permissions
	 * @return Role The object instance for method chaining
	 */
	public function setPermissions(Collection $value = null)
	{
		$this->permissions = $value;

		return $this;
	}
}
