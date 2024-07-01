<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Provider extends Entity
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
	protected $npiNumber;

	/**
	 * @access protected
	 * @var Person
	 */
	protected $person;

	/**
	 * @access protected
	 * @var string
	 */
	protected $position;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $states;


	/**
	 * Instantiate a new Provider
	 */
	public function __construct()
	{
		$this->states = new ArrayCollection();
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
	 * Get the value of npiNumber
	 *
	 * @access public
	 * @return string The value of npiNumber
	 */
	public function getNpiNumber()
	{
		return $this->npiNumber;
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
	 * Get the value of position
	 *
	 * @access public
	 * @return string The value of position
	 */
	public function getPosition()
	{
		return $this->position;
	}


	/**
	 * Get the value of states
	 *
	 * @access public
	 * @return Collection The value of states
	 */
	public function getStates()
	{
		return $this->states;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Provider The object instance for method chaining
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
	 * @return Provider The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of npiNumber
	 *
	 * @access public
	 * @param string $value The value to set to npiNumber
	 * @return Provider The object instance for method chaining
	 */
	public function setNpiNumber($value)
	{
		$this->npiNumber = $value;

		return $this;
	}


	/**
	 * Set the value of person
	 *
	 * @access public
	 * @param Person $value The value to set to person
	 * @return Provider The object instance for method chaining
	 */
	public function setPerson(\Person $value = null)
	{
		$this->person = $value;

		return $this;
	}


	/**
	 * Set the value of position
	 *
	 * @access public
	 * @param string $value The value to set to position
	 * @return Provider The object instance for method chaining
	 */
	public function setPosition($value)
	{
		$this->position = $value;

		return $this;
	}


	/**
	 * Set the value of states
	 *
	 * @access public
	 * @param Collection $value The value to set to states
	 * @return Provider The object instance for method chaining
	 */
	public function setStates(Collection $value = null)
	{
		$this->states = $value;

		return $this;
	}
}
