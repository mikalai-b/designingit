<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Consultation extends Entity
{
	/**
	 * @access protected
	 * @var Collection
	 */
	protected $answers;

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
	 * @var json
	 */
	protected $diagnosis;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $letter;

	/**
	 * @access protected
	 * @var Order
	 */
	protected $order;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $photos;

	/**
	 * @access protected
	 * @var json
	 */
	protected $physicalExam;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $prescriptions;

	/**
	 * @access protected
	 * @var string
	 */
	protected $status;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $symptoms;


	/**
	 * Instantiate a new Consultation
	 */
	public function __construct()
	{
		$this->answers = new ArrayCollection();
		$this->photos = new ArrayCollection();
		$this->prescriptions = new ArrayCollection();
		$this->symptoms = new ArrayCollection();
	}


	/**
	 * Get the value of answers
	 *
	 * @access public
	 * @return Collection The value of answers
	 */
	public function getAnswers()
	{
		return $this->answers;
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
	 * Get the value of diagnosis
	 *
	 * @access public
	 * @return json The value of diagnosis
	 */
	public function getDiagnosis()
	{
		return $this->diagnosis;
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
	 * Get the value of letter
	 *
	 * @access public
	 * @return string The value of letter
	 */
	public function getLetter()
	{
		return $this->letter;
	}


	/**
	 * Get the value of order
	 *
	 * @access public
	 * @return Order The value of order
	 */
	public function getOrder()
	{
		return $this->order;
	}


	/**
	 * Get the value of photos
	 *
	 * @access public
	 * @return Collection The value of photos
	 */
	public function getPhotos()
	{
		return $this->photos;
	}


	/**
	 * Get the value of physicalExam
	 *
	 * @access public
	 * @return json The value of physicalExam
	 */
	public function getPhysicalExam()
	{
		return $this->physicalExam;
	}


	/**
	 * Get the value of prescriptions
	 *
	 * @access public
	 * @return Collection The value of prescriptions
	 */
	public function getPrescriptions()
	{
		return $this->prescriptions;
	}


	/**
	 * Get the value of status
	 *
	 * @access public
	 * @return string The value of status
	 */
	public function getStatus()
	{
		return $this->status;
	}


	/**
	 * Get the value of symptoms
	 *
	 * @access public
	 * @return Collection The value of symptoms
	 */
	public function getSymptoms()
	{
		return $this->symptoms;
	}


	/**
	 * Set the value of answers
	 *
	 * @access public
	 * @param Collection $value The value to set to answers
	 * @return Consultation The object instance for method chaining
	 */
	public function setAnswers(Collection $value = null)
	{
		$this->answers = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Consultation The object instance for method chaining
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
	 * @return Consultation The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of diagnosis
	 *
	 * @access public
	 * @param json $value The value to set to diagnosis
	 * @return Consultation The object instance for method chaining
	 */
	public function setDiagnosis($value)
	{
		$this->diagnosis = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Consultation The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of letter
	 *
	 * @access public
	 * @param string $value The value to set to letter
	 * @return Consultation The object instance for method chaining
	 */
	public function setLetter($value)
	{
		$this->letter = $value;

		return $this;
	}


	/**
	 * Set the value of order
	 *
	 * @access public
	 * @param Order $value The value to set to order
	 * @return Consultation The object instance for method chaining
	 */
	public function setOrder(\Order $value = null)
	{
		$this->order = $value;

		return $this;
	}


	/**
	 * Set the value of photos
	 *
	 * @access public
	 * @param Collection $value The value to set to photos
	 * @return Consultation The object instance for method chaining
	 */
	public function setPhotos(Collection $value = null)
	{
		$this->photos = $value;

		return $this;
	}


	/**
	 * Set the value of physicalExam
	 *
	 * @access public
	 * @param json $value The value to set to physicalExam
	 * @return Consultation The object instance for method chaining
	 */
	public function setPhysicalExam($value)
	{
		$this->physicalExam = $value;

		return $this;
	}


	/**
	 * Set the value of prescriptions
	 *
	 * @access public
	 * @param Collection $value The value to set to prescriptions
	 * @return Consultation The object instance for method chaining
	 */
	public function setPrescriptions(Collection $value = null)
	{
		$this->prescriptions = $value;

		return $this;
	}


	/**
	 * Set the value of status
	 *
	 * @access public
	 * @param string $value The value to set to status
	 * @return Consultation The object instance for method chaining
	 */
	public function setStatus($value)
	{
		$this->status = $value;

		return $this;
	}


	/**
	 * Set the value of symptoms
	 *
	 * @access public
	 * @param Collection $value The value to set to symptoms
	 * @return Consultation The object instance for method chaining
	 */
	public function setSymptoms(Collection $value = null)
	{
		$this->symptoms = $value;

		return $this;
	}
}
