<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Account extends Entity
{
	/**
	 * @access protected
	 * @var string
	 */
	protected $customer;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateCreated;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateLastLoggedIn;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateModified;

	/**
	 * @access protected
	 * @var string
	 */
	protected $password;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $permissions;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $person;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $roles;

	/**
	 * @access protected
	 * @var string
	 */
	protected $token;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $tokenExpiry;


	/**
	 * Instantiate a new Account
	 */
	public function __construct()
	{
		$this->roles = new ArrayCollection();
		$this->permissions = new ArrayCollection();
	}


	/**
	 * Get the value of customer
	 *
	 * @access public
	 * @return string The value of customer
	 */
	public function getCustomer()
	{
		return $this->customer;
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
	 * Get the value of dateLastLoggedIn
	 *
	 * @access public
	 * @return \DateTime The value of dateLastLoggedIn
	 */
	public function getDateLastLoggedIn()
	{
		return $this->dateLastLoggedIn;
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
	 * Get the value of password
	 *
	 * @access public
	 * @return string The value of password
	 */
	public function getPassword()
	{
		return $this->password;
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
	 * Get the value of person
	 *
	 * @access public
	 * @return Person The value of person
	 */
	public function getPerson()
	{
		return $this->person;
	}


	/**
	 * Get the value of roles
	 *
	 * @access public
	 * @return Collection The value of roles
	 */
	public function getRoles()
	{
		return $this->roles;
	}


	/**
	 * Get the value of token
	 *
	 * @access public
	 * @return string The value of token
	 */
	public function getToken()
	{
		return $this->token;
	}


	/**
	 * Get the value of tokenExpiry
	 *
	 * @access public
	 * @return \DateTime The value of tokenExpiry
	 */
	public function getTokenExpiry()
	{
		return $this->tokenExpiry;
	}


	/**
	 * Set the value of customer
	 *
	 * @access public
	 * @param string $value The value to set to customer
	 * @return Account The object instance for method chaining
	 */
	public function setCustomer($value)
	{
		$this->customer = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Account The object instance for method chaining
	 */
	public function setDateCreated($value)
	{
		$this->dateCreated = $value;

		return $this;
	}


	/**
	 * Set the value of dateLastLoggedIn
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateLastLoggedIn
	 * @return Account The object instance for method chaining
	 */
	public function setDateLastLoggedIn($value)
	{
		$this->dateLastLoggedIn = $value;

		return $this;
	}


	/**
	 * Set the value of dateModified
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateModified
	 * @return Account The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of password
	 *
	 * @access public
	 * @param string $value The value to set to password
	 * @return Account The object instance for method chaining
	 */
	public function setPassword($value)
	{
		$this->password = $value;

		return $this;
	}


	/**
	 * Set the value of permissions
	 *
	 * @access public
	 * @param Collection $value The value to set to permissions
	 * @return Account The object instance for method chaining
	 */
	public function setPermissions(Collection $value = null)
	{
		$this->permissions = $value;

		return $this;
	}


	/**
	 * Set the value of person
	 *
	 * @access public
	 * @param Person $value The value to set to person
	 * @return Account The object instance for method chaining
	 */
	public function setPerson(\Person $value = null)
	{
		$this->person = $value;

		return $this;
	}


	/**
	 * Set the value of roles
	 *
	 * @access public
	 * @param Collection $value The value to set to roles
	 * @return Account The object instance for method chaining
	 */
	public function setRoles(Collection $value = null)
	{
		$this->roles = $value;

		return $this;
	}


	/**
	 * Set the value of token
	 *
	 * @access public
	 * @param string $value The value to set to token
	 * @return Account The object instance for method chaining
	 */
	public function setToken($value)
	{
		$this->token = $value;

		return $this;
	}


	/**
	 * Set the value of tokenExpiry
	 *
	 * @access public
	 * @param \DateTime $value The value to set to tokenExpiry
	 * @return Account The object instance for method chaining
	 */
	public function setTokenExpiry($value)
	{
		$this->tokenExpiry = $value;

		return $this;
	}
}
