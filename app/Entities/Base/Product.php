<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Product extends Entity
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $availableDashboardPeriods;

	/**
	 * @access protected
	 * @var array
	 */
	protected $availablePeriods;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $couponCodes;

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
	protected $defaultAutoRenewal;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $defaultExpiration;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $defaultPeriod;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $defaultRefills;

	/**
	 * @access protected
	 * @var string
	 */
	protected $grouponContent;

	/**
	 * @access protected
	 * @var string
	 */
	protected $grouponPrice;

	/**
	 * @access protected
	 * @var uuid
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $info;

	/**
	 * @access protected
	 * @var string
	 */
	protected $invalidCodeMessage;

	/**
	 * @access protected
	 * @var string
	 */
	protected $name;

	/**
	 * @access protected
	 * @var string
	 */
	protected $ndcNumber;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $offers;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $photoTypes;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $prescriptionOnly;

	/**
	 * @access protected
	 * @var string
	 */
	protected $price;

	/**
	 * @access protected
	 * @var string
	 */
	protected $quantity;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $questions;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $requireAutoRenewal;

	/**
	 * @access protected
	 * @var string
	 */
	protected $slug;

	/**
	 * @access protected
	 * @var string
	 */
	protected $strength;

	/**
	 * @access protected
	 * @var string
	 */
	protected $thumbnail;

	/**
	 * @access protected
	 * @var ProductType
	 */
	protected $type;

    /**
     * @access protected
     * @var ProductCategory
     */
    protected $category;


    /**
	 * Instantiate a new Product
	 */
	public function __construct()
	{
		$this->offers = new ArrayCollection();
		$this->questions = new ArrayCollection();
		$this->photoTypes = new ArrayCollection();
		$this->couponCodes = new ArrayCollection();
	}


	/**
	 * Get the value of availableDashboardPeriods
	 *
	 * @access public
	 * @return array The value of availableDashboardPeriods
	 */
	public function getAvailableDashboardPeriods()
	{
		return $this->availableDashboardPeriods;
	}


	/**
	 * Get the value of availablePeriods
	 *
	 * @access public
	 * @return array The value of availablePeriods
	 */
	public function getAvailablePeriods()
	{
		return $this->availablePeriods;
	}


	/**
	 * Get the value of couponCodes
	 *
	 * @access public
	 * @return Collection The value of couponCodes
	 */
	public function getCouponCodes()
	{
		return $this->couponCodes;
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
	 * Get the value of defaultAutoRenewal
	 *
	 * @access public
	 * @return integer The value of defaultAutoRenewal
	 */
	public function getDefaultAutoRenewal()
	{
		return $this->defaultAutoRenewal;
	}


	/**
	 * Get the value of defaultExpiration
	 *
	 * @access public
	 * @return integer The value of defaultExpiration
	 */
	public function getDefaultExpiration()
	{
		return $this->defaultExpiration;
	}


	/**
	 * Get the value of defaultPeriod
	 *
	 * @access public
	 * @return integer The value of defaultPeriod
	 */
	public function getDefaultPeriod()
	{
		return $this->defaultPeriod;
	}


	/**
	 * Get the value of defaultRefills
	 *
	 * @access public
	 * @return integer The value of defaultRefills
	 */
	public function getDefaultRefills()
	{
		return $this->defaultRefills;
	}


	/**
	 * Get the value of grouponContent
	 *
	 * @access public
	 * @return string The value of grouponContent
	 */
	public function getGrouponContent()
	{
		return $this->grouponContent;
	}


	/**
	 * Get the value of grouponPrice
	 *
	 * @access public
	 * @return string The value of grouponPrice
	 */
	public function getGrouponPrice()
	{
		return $this->grouponPrice;
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
	 * Get the value of info
	 *
	 * @access public
	 * @return string The value of info
	 */
	public function getInfo()
	{
		return $this->info;
	}


	/**
	 * Get the value of invalidCodeMessage
	 *
	 * @access public
	 * @return string The value of invalidCodeMessage
	 */
	public function getInvalidCodeMessage()
	{
		return $this->invalidCodeMessage;
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
	 * Get the value of ndcNumber
	 *
	 * @access public
	 * @return string The value of ndcNumber
	 */
	public function getNdcNumber()
	{
		return $this->ndcNumber;
	}


	/**
	 * Get the value of offers
	 *
	 * @access public
	 * @return Collection The value of offers
	 */
	public function getOffers()
	{
		return $this->offers;
	}


	/**
	 * Get the value of photoTypes
	 *
	 * @access public
	 * @return Collection The value of photoTypes
	 */
	public function getPhotoTypes()
	{
		return $this->photoTypes;
	}


	/**
	 * Get the value of prescriptionOnly
	 *
	 * @access public
	 * @return boolean The value of prescriptionOnly
	 */
	public function getPrescriptionOnly()
	{
		return $this->prescriptionOnly;
	}


	/**
	 * Get the value of price
	 *
	 * @access public
	 * @return string The value of price
	 */
	public function getPrice()
	{
		return $this->price;
	}


	/**
	 * Get the value of quantity
	 *
	 * @access public
	 * @return string The value of quantity
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}


	/**
	 * Get the value of questions
	 *
	 * @access public
	 * @return Collection The value of questions
	 */
	public function getQuestions()
	{
		return $this->questions;
	}


	/**
	 * Get the value of requireAutoRenewal
	 *
	 * @access public
	 * @return boolean The value of requireAutoRenewal
	 */
	public function getRequireAutoRenewal()
	{
		return $this->requireAutoRenewal;
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
	 * Get the value of strength
	 *
	 * @access public
	 * @return string The value of strength
	 */
	public function getStrength()
	{
		return $this->strength;
	}


	/**
	 * Get the value of thumbnail
	 *
	 * @access public
	 * @return string The value of thumbnail
	 */
	public function getThumbnail()
	{
		return $this->thumbnail;
	}


	/**
	 * Get the value of type
	 *
	 * @access public
	 * @return ProductType The value of type
	 */
	public function getType()
	{
		return $this->type;
	}

    /**
     * Get the value of category
     *
     * @access public
     * @return ProductCategory The value of type
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
	 * Set the value of availableDashboardPeriods
	 *
	 * @access public
	 * @param array $value The value to set to availableDashboardPeriods
	 * @return Product The object instance for method chaining
	 */
	public function setAvailableDashboardPeriods($value)
	{
		$this->availableDashboardPeriods = $value;

		return $this;
	}


	/**
	 * Set the value of availablePeriods
	 *
	 * @access public
	 * @param array $value The value to set to availablePeriods
	 * @return Product The object instance for method chaining
	 */
	public function setAvailablePeriods($value)
	{
		$this->availablePeriods = $value;

		return $this;
	}


	/**
	 * Set the value of couponCodes
	 *
	 * @access public
	 * @param Collection $value The value to set to couponCodes
	 * @return Product The object instance for method chaining
	 */
	public function setCouponCodes(Collection $value = null)
	{
		$this->couponCodes = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Product The object instance for method chaining
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
	 * @return Product The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of defaultAutoRenewal
	 *
	 * @access public
	 * @param integer $value The value to set to defaultAutoRenewal
	 * @return Product The object instance for method chaining
	 */
	public function setDefaultAutoRenewal($value)
	{
		$this->defaultAutoRenewal = $value;

		return $this;
	}


	/**
	 * Set the value of defaultExpiration
	 *
	 * @access public
	 * @param integer $value The value to set to defaultExpiration
	 * @return Product The object instance for method chaining
	 */
	public function setDefaultExpiration($value)
	{
		$this->defaultExpiration = $value;

		return $this;
	}


	/**
	 * Set the value of defaultPeriod
	 *
	 * @access public
	 * @param integer $value The value to set to defaultPeriod
	 * @return Product The object instance for method chaining
	 */
	public function setDefaultPeriod($value)
	{
		$this->defaultPeriod = $value;

		return $this;
	}


	/**
	 * Set the value of defaultRefills
	 *
	 * @access public
	 * @param integer $value The value to set to defaultRefills
	 * @return Product The object instance for method chaining
	 */
	public function setDefaultRefills($value)
	{
		$this->defaultRefills = $value;

		return $this;
	}


	/**
	 * Set the value of grouponContent
	 *
	 * @access public
	 * @param string $value The value to set to grouponContent
	 * @return Product The object instance for method chaining
	 */
	public function setGrouponContent($value)
	{
		$this->grouponContent = $value;

		return $this;
	}


	/**
	 * Set the value of grouponPrice
	 *
	 * @access public
	 * @param string $value The value to set to grouponPrice
	 * @return Product The object instance for method chaining
	 */
	public function setGrouponPrice($value)
	{
		$this->grouponPrice = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param uuid $value The value to set to id
	 * @return Product The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of info
	 *
	 * @access public
	 * @param string $value The value to set to info
	 * @return Product The object instance for method chaining
	 */
	public function setInfo($value)
	{
		$this->info = $value;

		return $this;
	}


	/**
	 * Set the value of invalidCodeMessage
	 *
	 * @access public
	 * @param string $value The value to set to invalidCodeMessage
	 * @return Product The object instance for method chaining
	 */
	public function setInvalidCodeMessage($value)
	{
		$this->invalidCodeMessage = $value;

		return $this;
	}


	/**
	 * Set the value of name
	 *
	 * @access public
	 * @param string $value The value to set to name
	 * @return Product The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of ndcNumber
	 *
	 * @access public
	 * @param string $value The value to set to ndcNumber
	 * @return Product The object instance for method chaining
	 */
	public function setNdcNumber($value)
	{
		$this->ndcNumber = $value;

		return $this;
	}


	/**
	 * Set the value of offers
	 *
	 * @access public
	 * @param Collection $value The value to set to offers
	 * @return Product The object instance for method chaining
	 */
	public function setOffers(Collection $value = null)
	{
		$this->offers = $value;

		return $this;
	}


	/**
	 * Set the value of photoTypes
	 *
	 * @access public
	 * @param Collection $value The value to set to photoTypes
	 * @return Product The object instance for method chaining
	 */
	public function setPhotoTypes(Collection $value = null)
	{
		$this->photoTypes = $value;

		return $this;
	}


	/**
	 * Set the value of prescriptionOnly
	 *
	 * @access public
	 * @param boolean $value The value to set to prescriptionOnly
	 * @return Product The object instance for method chaining
	 */
	public function setPrescriptionOnly($value)
	{
		$this->prescriptionOnly = $value;

		return $this;
	}


	/**
	 * Set the value of price
	 *
	 * @access public
	 * @param string $value The value to set to price
	 * @return Product The object instance for method chaining
	 */
	public function setPrice($value)
	{
		$this->price = $value;

		return $this;
	}


	/**
	 * Set the value of quantity
	 *
	 * @access public
	 * @param string $value The value to set to quantity
	 * @return Product The object instance for method chaining
	 */
	public function setQuantity($value)
	{
		$this->quantity = $value;

		return $this;
	}


	/**
	 * Set the value of questions
	 *
	 * @access public
	 * @param Collection $value The value to set to questions
	 * @return Product The object instance for method chaining
	 */
	public function setQuestions(Collection $value = null)
	{
		$this->questions = $value;

		return $this;
	}


	/**
	 * Set the value of requireAutoRenewal
	 *
	 * @access public
	 * @param boolean $value The value to set to requireAutoRenewal
	 * @return Product The object instance for method chaining
	 */
	public function setRequireAutoRenewal($value)
	{
		$this->requireAutoRenewal = $value;

		return $this;
	}


	/**
	 * Set the value of slug
	 *
	 * @access public
	 * @param string $value The value to set to slug
	 * @return Product The object instance for method chaining
	 */
	public function setSlug($value)
	{
		$this->slug = $value;

		return $this;
	}


	/**
	 * Set the value of strength
	 *
	 * @access public
	 * @param string $value The value to set to strength
	 * @return Product The object instance for method chaining
	 */
	public function setStrength($value)
	{
		$this->strength = $value;

		return $this;
	}


	/**
	 * Set the value of thumbnail
	 *
	 * @access public
	 * @param string $value The value to set to thumbnail
	 * @return Product The object instance for method chaining
	 */
	public function setThumbnail($value)
	{
		$this->thumbnail = $value;

		return $this;
	}


	/**
	 * Set the value of type
	 *
	 * @access public
	 * @param ProductType $value The value to set to type
	 * @return Product The object instance for method chaining
	 */
	public function setType(\ProductType $value = null)
	{
		$this->type = $value;

		return $this;
	}

    /**
     * Set the value of type
     *
     * @access public
     * @param ProductCategory $value The value to set to category
     * @return Product The object instance for method chaining
     */
    public function setCategory(\ProductCategory $value = null)
    {
        $this->category = $value;

        return $this;
    }

}
