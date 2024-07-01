<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Photo extends Entity
{
	/**
	 * @access protected
	 * @var Consultation
	 */
	protected $consultation;

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
	protected $file;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var PhotoType
	 */
	protected $type;


	/**
	 * Instantiate a new Photo
	 */
	public function __construct()
	{
	}


	/**
	 * Get the value of consultation
	 *
	 * @access public
	 * @return Consultation The value of consultation
	 */
	public function getConsultation()
	{
		return $this->consultation;
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
	 * Get the value of file
	 *
	 * @access public
	 * @return string The value of file
	 */
	public function getFile()
	{
		return $this->file;
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
	 * Get the value of type
	 *
	 * @access public
	 * @return PhotoType The value of type
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * Set the value of consultation
	 *
	 * @access public
	 * @param Consultation $value The value to set to consultation
	 * @return Photo The object instance for method chaining
	 */
	public function setConsultation(\Consultation $value = null)
	{
		$this->consultation = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Photo The object instance for method chaining
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
	 * @return Photo The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of file
	 *
	 * @access public
	 * @param string $value The value to set to file
	 * @return Photo The object instance for method chaining
	 */
	public function setFile($value)
	{
		$this->file = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Photo The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of type
	 *
	 * @access public
	 * @param PhotoType $value The value to set to type
	 * @return Photo The object instance for method chaining
	 */
	public function setType(\PhotoType $value = null)
	{
		$this->type = $value;

		return $this;
	}
}
