<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class State extends Entity
{
	/**
	 * @access protected
	 * @var string
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
	protected $providers;


	/**
	 * Instantiate a new State
	 */
	public function __construct()
	{
		$this->providers = new ArrayCollection();
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
	 * Get the value of providers
	 *
	 * @access public
	 * @return Collection The value of providers
	 */
	public function getProviders()
	{
		return $this->providers;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param string $value The value to set to id
	 * @return State The object instance for method chaining
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
	 * @return State The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of providers
	 *
	 * @access public
	 * @param Collection $value The value to set to providers
	 * @return State The object instance for method chaining
	 */
	public function setProviders(Collection $value = null)
	{
		$this->providers = $value;

		return $this;
	}
}
