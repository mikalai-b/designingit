<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class CouponCode extends Entity
{
	/**
	 * @access protected
	 * @var Campaign
	 */
	protected $campaign;

	/**
	 * @access protected
	 * @var string
	 */
	protected $campaignDescription;

	/**
	 * @access protected
	 * @var string
	 */
	protected $code;

	/**
	 * @access protected
	 * @var \DateTime
	 */
	protected $dateRedeemed;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $products;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $redeemed;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $redemptionCount;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $redemptionLimit;

	/**
	 * @access protected
	 * @var boolean
	 */
	protected $unlimited;


	/**
	 * Instantiate a new CouponCode
	 */
	public function __construct()
	{
		$this->products = new ArrayCollection();
	}


	/**
	 * Get the value of campaign
	 *
	 * @access public
	 * @return Campaign The value of campaign
	 */
	public function getCampaign()
	{
		return $this->campaign;
	}


	/**
	 * Get the value of campaignDescription
	 *
	 * @access public
	 * @return string The value of campaignDescription
	 */
	public function getCampaignDescription()
	{
		return $this->campaignDescription;
	}


	/**
	 * Get the value of code
	 *
	 * @access public
	 * @return string The value of code
	 */
	public function getCode()
	{
		return $this->code;
	}


	/**
	 * Get the value of dateRedeemed
	 *
	 * @access public
	 * @return \DateTime The value of dateRedeemed
	 */
	public function getDateRedeemed()
	{
		return $this->dateRedeemed;
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
	 * Get the value of redeemed
	 *
	 * @access public
	 * @return boolean The value of redeemed
	 */
	public function getRedeemed()
	{
		return $this->redeemed;
	}


	/**
	 * Get the value of redemptionCount
	 *
	 * @access public
	 * @return integer The value of redemptionCount
	 */
	public function getRedemptionCount()
	{
		return $this->redemptionCount;
	}


	/**
	 * Get the value of redemptionLimit
	 *
	 * @access public
	 * @return integer The value of redemptionLimit
	 */
	public function getRedemptionLimit()
	{
		return $this->redemptionLimit;
	}


	/**
	 * Get the value of unlimited
	 *
	 * @access public
	 * @return boolean The value of unlimited
	 */
	public function getUnlimited()
	{
		return $this->unlimited;
	}


	/**
	 * Set the value of campaign
	 *
	 * @access public
	 * @param Campaign $value The value to set to campaign
	 * @return CouponCode The object instance for method chaining
	 */
	public function setCampaign(\Campaign $value = null)
	{
		$this->campaign = $value;

		return $this;
	}


	/**
	 * Set the value of campaignDescription
	 *
	 * @access public
	 * @param string $value The value to set to campaignDescription
	 * @return CouponCode The object instance for method chaining
	 */
	public function setCampaignDescription($value)
	{
		$this->campaignDescription = $value;

		return $this;
	}


	/**
	 * Set the value of code
	 *
	 * @access public
	 * @param string $value The value to set to code
	 * @return CouponCode The object instance for method chaining
	 */
	public function setCode($value)
	{
		$this->code = $value;

		return $this;
	}


	/**
	 * Set the value of dateRedeemed
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateRedeemed
	 * @return CouponCode The object instance for method chaining
	 */
	public function setDateRedeemed($value)
	{
		$this->dateRedeemed = $value;

		return $this;
	}


	/**
	 * Set the value of products
	 *
	 * @access public
	 * @param Collection $value The value to set to products
	 * @return CouponCode The object instance for method chaining
	 */
	public function setProducts(Collection $value = null)
	{
		$this->products = $value;

		return $this;
	}


	/**
	 * Set the value of redeemed
	 *
	 * @access public
	 * @param boolean $value The value to set to redeemed
	 * @return CouponCode The object instance for method chaining
	 */
	public function setRedeemed($value)
	{
		$this->redeemed = $value;

		return $this;
	}


	/**
	 * Set the value of redemptionCount
	 *
	 * @access public
	 * @param integer $value The value to set to redemptionCount
	 * @return CouponCode The object instance for method chaining
	 */
	public function setRedemptionCount($value)
	{
		$this->redemptionCount = $value;

		return $this;
	}


	/**
	 * Set the value of redemptionLimit
	 *
	 * @access public
	 * @param integer $value The value to set to redemptionLimit
	 * @return CouponCode The object instance for method chaining
	 */
	public function setRedemptionLimit($value)
	{
		$this->redemptionLimit = $value;

		return $this;
	}


	/**
	 * Set the value of unlimited
	 *
	 * @access public
	 * @param boolean $value The value to set to unlimited
	 * @return CouponCode The object instance for method chaining
	 */
	public function setUnlimited($value)
	{
		$this->unlimited = $value;

		return $this;
	}
}
