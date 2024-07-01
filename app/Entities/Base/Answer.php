<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Answer extends Entity
{
	/**
	 * @access protected
	 * @var Consultation
	 */
	protected $consultation;

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
	 * @var uuid
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $note;

	/**
	 * @access protected
	 * @var string
	 */
	protected $question;

	/**
	 * @access protected
	 * @var json
	 */
	protected $questionConfig;

	/**
	 * @access protected
	 * @var QuestionType
	 */
	protected $type;


	/**
	 * Instantiate a new Answer
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
	 * @return uuid The value of id
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Get the value of note
	 *
	 * @access public
	 * @return string The value of note
	 */
	public function getNote()
	{
		return $this->note;
	}


	/**
	 * Get the value of question
	 *
	 * @access public
	 * @return string The value of question
	 */
	public function getQuestion()
	{
		return $this->question;
	}


	/**
	 * Get the value of questionConfig
	 *
	 * @access public
	 * @return json The value of questionConfig
	 */
	public function getQuestionConfig()
	{
		return $this->questionConfig;
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
	 * Set the value of consultation
	 *
	 * @access public
	 * @param Consultation $value The value to set to consultation
	 * @return Answer The object instance for method chaining
	 */
	public function setConsultation(\Consultation $value = null)
	{
		$this->consultation = $value;

		return $this;
	}


	/**
	 * Set the value of content
	 *
	 * @access public
	 * @param string $value The value to set to content
	 * @return Answer The object instance for method chaining
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
	 * @return Answer The object instance for method chaining
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
	 * @return Answer The object instance for method chaining
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
	 * @return Answer The object instance for method chaining
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
	 * @param uuid $value The value to set to id
	 * @return Answer The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of note
	 *
	 * @access public
	 * @param string $value The value to set to note
	 * @return Answer The object instance for method chaining
	 */
	public function setNote($value)
	{
		$this->note = $value;

		return $this;
	}


	/**
	 * Set the value of question
	 *
	 * @access public
	 * @param string $value The value to set to question
	 * @return Answer The object instance for method chaining
	 */
	public function setQuestion($value)
	{
		$this->question = $value;

		return $this;
	}


	/**
	 * Set the value of questionConfig
	 *
	 * @access public
	 * @param json $value The value to set to questionConfig
	 * @return Answer The object instance for method chaining
	 */
	public function setQuestionConfig($value)
	{
		$this->questionConfig = $value;

		return $this;
	}


	/**
	 * Set the value of type
	 *
	 * @access public
	 * @param QuestionType $value The value to set to type
	 * @return Answer The object instance for method chaining
	 */
	public function setType(\QuestionType $value = null)
	{
		$this->type = $value;

		return $this;
	}
}
