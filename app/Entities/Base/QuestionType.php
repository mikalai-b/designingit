<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class QuestionType extends Entity
{
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
	 * @var string
	 */
	protected $template;


	/**
	 * Instantiate a new QuestionType
	 */
	public function __construct()
	{
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
	 * Get the value of template
	 *
	 * @access public
	 * @return string The value of template
	 */
	public function getTemplate()
	{
		return $this->template;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return QuestionType The object instance for method chaining
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
	 * @return QuestionType The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of template
	 *
	 * @access public
	 * @param string $value The value to set to template
	 * @return QuestionType The object instance for method chaining
	 */
	public function setTemplate($value)
	{
		$this->template = $value;

		return $this;
	}
}
