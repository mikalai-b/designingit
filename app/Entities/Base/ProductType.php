<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class ProductType extends Entity
{
	/**
	 * @access protected
	 * @var string
	 */
	protected $approvedTemplate;

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
	 * @var string
	 */
	protected $consentForm;

	/**
	 * @access protected
	 * @var string
	 */
	protected $declinedTemplate;

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
	protected $directions;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $instructionsTemplate;

	/**
	 * @access protected
	 * @var string
	 */
	protected $name;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $products;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $requireAutoRenewal;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $symptoms;


	/**
	 * Instantiate a new ProductType
	 */
	public function __construct()
	{
		$this->products = new ArrayCollection();
		$this->symptoms = new ArrayCollection();
	}


	/**
	 * Get the value of approvedTemplate
	 *
	 * @access public
	 * @return string The value of approvedTemplate
	 */
	public function getApprovedTemplate()
	{
		return $this->approvedTemplate;
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
	 * Get the value of consentForm
	 *
	 * @access public
	 * @return string The value of consentForm
	 */
	public function getConsentForm()
	{
		return $this->consentForm;
	}


	/**
	 * Get the value of declinedTemplate
	 *
	 * @access public
	 * @return string The value of declinedTemplate
	 */
	public function getDeclinedTemplate()
	{
		return $this->declinedTemplate;
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
	 * Get the value of directions
	 *
	 * @access public
	 * @return string The value of directions
	 */
	public function getDirections()
	{
		return $this->directions;
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
	 * Get the value of instructionsTemplate
	 *
	 * @access public
	 * @return string The value of instructionsTemplate
	 */
	public function getInstructionsTemplate()
	{
		return $this->instructionsTemplate;
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
	 * Get the value of products
	 *
	 * @access public
	 * @return Collection The value of products
	 */
	public function getProducts()
	{
		return $this->products;
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
	 * Set the value of approvedTemplate
	 *
	 * @access public
	 * @param string $value The value to set to approvedTemplate
	 * @return ProductType The object instance for method chaining
	 */
	public function setApprovedTemplate($value)
	{
		$this->approvedTemplate = $value;

		return $this;
	}


	/**
	 * Set the value of availableDashboardPeriods
	 *
	 * @access public
	 * @param array $value The value to set to availableDashboardPeriods
	 * @return ProductType The object instance for method chaining
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
	 * @return ProductType The object instance for method chaining
	 */
	public function setAvailablePeriods($value)
	{
		$this->availablePeriods = $value;

		return $this;
	}


	/**
	 * Set the value of consentForm
	 *
	 * @access public
	 * @param string $value The value to set to consentForm
	 * @return ProductType The object instance for method chaining
	 */
	public function setConsentForm($value)
	{
		$this->consentForm = $value;

		return $this;
	}


	/**
	 * Set the value of declinedTemplate
	 *
	 * @access public
	 * @param string $value The value to set to declinedTemplate
	 * @return ProductType The object instance for method chaining
	 */
	public function setDeclinedTemplate($value)
	{
		$this->declinedTemplate = $value;

		return $this;
	}


	/**
	 * Set the value of defaultAutoRenewal
	 *
	 * @access public
	 * @param integer $value The value to set to defaultAutoRenewal
	 * @return ProductType The object instance for method chaining
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
	 * @return ProductType The object instance for method chaining
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
	 * @return ProductType The object instance for method chaining
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
	 * @return ProductType The object instance for method chaining
	 */
	public function setDefaultRefills($value)
	{
		$this->defaultRefills = $value;

		return $this;
	}


	/**
	 * Set the value of directions
	 *
	 * @access public
	 * @param string $value The value to set to directions
	 * @return ProductType The object instance for method chaining
	 */
	public function setDirections($value)
	{
		$this->directions = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return ProductType The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of instructionsTemplate
	 *
	 * @access public
	 * @param string $value The value to set to instructionsTemplate
	 * @return ProductType The object instance for method chaining
	 */
	public function setInstructionsTemplate($value)
	{
		$this->instructionsTemplate = $value;

		return $this;
	}


	/**
	 * Set the value of name
	 *
	 * @access public
	 * @param string $value The value to set to name
	 * @return ProductType The object instance for method chaining
	 */
	public function setName($value)
	{
		$this->name = $value;

		return $this;
	}


	/**
	 * Set the value of products
	 *
	 * @access public
	 * @param Collection $value The value to set to products
	 * @return ProductType The object instance for method chaining
	 */
	public function setProducts(Collection $value = null)
	{
		$this->products = $value;

		return $this;
	}


	/**
	 * Set the value of requireAutoRenewal
	 *
	 * @access public
	 * @param boolean $value The value to set to requireAutoRenewal
	 * @return ProductType The object instance for method chaining
	 */
	public function setRequireAutoRenewal($value)
	{
		$this->requireAutoRenewal = $value;

		return $this;
	}


	/**
	 * Set the value of symptoms
	 *
	 * @access public
	 * @param Collection $value The value to set to symptoms
	 * @return ProductType The object instance for method chaining
	 */
	public function setSymptoms(Collection $value = null)
	{
		$this->symptoms = $value;

		return $this;
	}
}
