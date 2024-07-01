<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;


class ProductCategory extends Entity
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
    protected $slug;

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
     * @access protected
     * @var Collection
     */
    protected $products;

    /**
     * Instantiate a new ProductCategory
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Get the value of slug
     *
     * @access public
     * @return string The value of slug
     */
    public function getSlug()
    {
        return $this->slug;
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
     * Get the value of products
     *
     * @access public
     * @return Collection The value of products
     */
    public function getProducts()
    {
        return $this->products;
    }
}
