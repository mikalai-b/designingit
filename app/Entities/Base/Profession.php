<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Entity;

class Profession extends Entity
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
    protected $title;

    /**
     * @access protected
     * @var string
     */
    protected $description;

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
     * Instantiate a new Account
     */
    public function __construct(){}

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
     * Get the value of title
     *
     * @access public
     * @return string The value of title
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set the value of id
     *
     * @access public
     * @param integer $value The value to set to id
     * @return Profession The object instance for method chaining
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }
}
