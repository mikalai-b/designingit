<?php namespace Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Entity;

class Person extends Entity
{
	/**
	 * @access protected
	 * @var Account
	 */
	protected $account;

	/**
	 * @access protected
	 * @var string
	 */
	protected $addressLine1;

	/**
	 * @access protected
	 * @var string
	 */
	protected $addressLine2;

	/**
	 * @access protected
	 * @var string
	 */
	protected $avatar;

	/**
	 * @access protected
	 * @var string
	 */
	protected $city;

	/**
	 * @access protected
	 * @var string
	 */
	protected $credentials;

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
	 * @var \DateTime
	 */
	protected $dateOfBirth;

	/**
	 * @access protected
	 * @var string
	 */
	protected $email;

	/**
	 * @access protected
	 * @var string
	 */
	protected $firstName;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $fulfillments;

	/**
	 * @access protected
	 * @var string
	 */
	protected $gender;

	/**
	 * @access protected
	 * @var integer
	 */
	protected $id;

	/**
	 * @access protected
	 * @var string
	 */
	protected $lastName;

	/**
	 * @access protected
	 * @var string
	 */
	protected $middleName;

	/**
	 * @access protected
	 * @var Collection
	 */
	protected $orders;

	/**
	 * @access protected
	 * @var string
	 */
	protected $phone;

	/**
	 * @access protected
	 * @var string
	 */
	protected $postalCode;

	/**
	 * @access protected
	 * @var string
	 */
	protected $rememberToken;

	/**
	 * @access protected
	 * @var State
	 */
	protected $state;

	/**
	 * @access protected
	 * @var string
	 */
	protected $title;

    /**
     * @access protected
     * @var Profession
     */
    protected $profession;

    /**
     * @access protected
     * @var integer
     */
    protected $professionId;
	/**
	 * Instantiate a new Person
	 */
	public function __construct()
	{
		$this->orders = new ArrayCollection();
		$this->fulfillments = new ArrayCollection();
	}


	/**
	 * Get the value of account
	 *
	 * @access public
	 * @return Account The value of account
	 */
	public function getAccount()
	{
		return $this->account;
	}


	/**
	 * Get the value of addressLine1
	 *
	 * @access public
	 * @return string The value of addressLine1
	 */
	public function getAddressLine1()
	{
		return $this->addressLine1;
	}


	/**
	 * Get the value of addressLine2
	 *
	 * @access public
	 * @return string The value of addressLine2
	 */
	public function getAddressLine2()
	{
		return $this->addressLine2;
	}


	/**
	 * Get the value of avatar
	 *
	 * @access public
	 * @return string The value of avatar
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}


	/**
	 * Get the value of city
	 *
	 * @access public
	 * @return string The value of city
	 */
	public function getCity()
	{
		return $this->city;
	}


	/**
	 * Get the value of credentials
	 *
	 * @access public
	 * @return string The value of credentials
	 */
	public function getCredentials()
	{
		return $this->credentials;
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
	 * Get the value of dateOfBirth
	 *
	 * @access public
	 * @return \DateTime The value of dateOfBirth
	 */
	public function getDateOfBirth()
	{
		return $this->dateOfBirth;
	}


	/**
	 * Get the value of email
	 *
	 * @access public
	 * @return string The value of email
	 */
	public function getEmail()
	{
		return $this->email;
	}


	/**
	 * Get the value of firstName
	 *
	 * @access public
	 * @return string The value of firstName
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}


	/**
	 * Get the value of fulfillments
	 *
	 * @access public
	 * @return Collection The value of fulfillments
	 */
	public function getFulfillments()
	{
		return $this->fulfillments;
	}


	/**
	 * Get the value of gender
	 *
	 * @access public
	 * @return string The value of gender
	 */
	public function getGender()
	{
		return $this->gender;
	}

    public function getGenderForSuiteRx()
    {
        return $this->gender === 'F' ? 'Female' : 'Male';
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
	 * Get the value of lastName
	 *
	 * @access public
	 * @return string The value of lastName
	 */
	public function getLastName()
	{
		return $this->lastName;
	}


	/**
	 * Get the value of middleName
	 *
	 * @access public
	 * @return string The value of middleName
	 */
	public function getMiddleName()
	{
		return $this->middleName;
	}


	/**
	 * Get the value of orders
	 *
	 * @access public
	 * @return Collection The value of orders
	 */
	public function getOrders()
	{
		return $this->orders;
	}


	/**
	 * Get the value of phone
	 *
	 * @access public
	 * @return string The value of phone
	 */
	public function getPhone()
	{
		return $this->phone;
	}


	/**
	 * Get the value of postalCode
	 *
	 * @access public
	 * @return string The value of postalCode
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}


	/**
	 * Get the value of rememberToken
	 *
	 * @access public
	 * @return string The value of rememberToken
	 */
	public function getRememberToken()
	{
		return $this->rememberToken;
	}


	/**
	 * Get the value of state
	 *
	 * @access public
	 * @return State The value of state
	 */
	public function getState()
	{
		return $this->state;
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
	 * Set the value of account
	 *
	 * @access public
	 * @param Account $value The value to set to account
	 * @return Person The object instance for method chaining
	 */
	public function setAccount(\Account $value = null)
	{
		$this->account = $value;

		return $this;
	}


	/**
	 * Set the value of addressLine1
	 *
	 * @access public
	 * @param string $value The value to set to addressLine1
	 * @return Person The object instance for method chaining
	 */
	public function setAddressLine1($value)
	{
		$this->addressLine1 = $value;

		return $this;
	}


	/**
	 * Set the value of addressLine2
	 *
	 * @access public
	 * @param string $value The value to set to addressLine2
	 * @return Person The object instance for method chaining
	 */
	public function setAddressLine2($value)
	{
		$this->addressLine2 = $value;

		return $this;
	}


	/**
	 * Set the value of avatar
	 *
	 * @access public
	 * @param string $value The value to set to avatar
	 * @return Person The object instance for method chaining
	 */
	public function setAvatar($value)
	{
		$this->avatar = $value;

		return $this;
	}


	/**
	 * Set the value of city
	 *
	 * @access public
	 * @param string $value The value to set to city
	 * @return Person The object instance for method chaining
	 */
	public function setCity($value)
	{
		$this->city = $value;

		return $this;
	}


	/**
	 * Set the value of credentials
	 *
	 * @access public
	 * @param string $value The value to set to credentials
	 * @return Person The object instance for method chaining
	 */
	public function setCredentials($value)
	{
		$this->credentials = $value;

		return $this;
	}


	/**
	 * Set the value of dateCreated
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateCreated
	 * @return Person The object instance for method chaining
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
	 * @return Person The object instance for method chaining
	 */
	public function setDateModified($value)
	{
		$this->dateModified = $value;

		return $this;
	}


	/**
	 * Set the value of dateOfBirth
	 *
	 * @access public
	 * @param \DateTime $value The value to set to dateOfBirth
	 * @return Person The object instance for method chaining
	 */
	public function setDateOfBirth($value)
	{
		$this->dateOfBirth = $value;

		return $this;
	}


	/**
	 * Set the value of email
	 *
	 * @access public
	 * @param string $value The value to set to email
	 * @return Person The object instance for method chaining
	 */
	public function setEmail($value)
	{
		$this->email = $value;

		return $this;
	}


	/**
	 * Set the value of firstName
	 *
	 * @access public
	 * @param string $value The value to set to firstName
	 * @return Person The object instance for method chaining
	 */
	public function setFirstName($value)
	{
		$this->firstName = $value;

		return $this;
	}


	/**
	 * Set the value of fulfillments
	 *
	 * @access public
	 * @param Collection $value The value to set to fulfillments
	 * @return Person The object instance for method chaining
	 */
	public function setFulfillments(Collection $value = null)
	{
		$this->fulfillments = $value;

		return $this;
	}


	/**
	 * Set the value of gender
	 *
	 * @access public
	 * @param string $value The value to set to gender
	 * @return Person The object instance for method chaining
	 */
	public function setGender($value)
	{
		$this->gender = $value;

		return $this;
	}


	/**
	 * Set the value of id
	 *
	 * @access public
	 * @param integer $value The value to set to id
	 * @return Person The object instance for method chaining
	 */
	public function setId($value)
	{
		$this->id = $value;

		return $this;
	}


	/**
	 * Set the value of lastName
	 *
	 * @access public
	 * @param string $value The value to set to lastName
	 * @return Person The object instance for method chaining
	 */
	public function setLastName($value)
	{
		$this->lastName = $value;

		return $this;
	}


	/**
	 * Set the value of middleName
	 *
	 * @access public
	 * @param string $value The value to set to middleName
	 * @return Person The object instance for method chaining
	 */
	public function setMiddleName($value)
	{
		$this->middleName = $value;

		return $this;
	}


	/**
	 * Set the value of orders
	 *
	 * @access public
	 * @param Collection $value The value to set to orders
	 * @return Person The object instance for method chaining
	 */
	public function setOrders(Collection $value = null)
	{
		$this->orders = $value;

		return $this;
	}


	/**
	 * Set the value of phone
	 *
	 * @access public
	 * @param string $value The value to set to phone
	 * @return Person The object instance for method chaining
	 */
	public function setPhone($value)
	{
		$this->phone = $value;

		return $this;
	}


	/**
	 * Set the value of postalCode
	 *
	 * @access public
	 * @param string $value The value to set to postalCode
	 * @return Person The object instance for method chaining
	 */
	public function setPostalCode($value)
	{
		$this->postalCode = $value;

		return $this;
	}


	/**
	 * Set the value of rememberToken
	 *
	 * @access public
	 * @param string $value The value to set to rememberToken
	 * @return Person The object instance for method chaining
	 */
	public function setRememberToken($value)
	{
		$this->rememberToken = $value;

		return $this;
	}


	/**
	 * Set the value of state
	 *
	 * @access public
	 * @param State $value The value to set to state
	 * @return Person The object instance for method chaining
	 */
	public function setState(\State $value = null)
	{
		$this->state = $value;

		return $this;
	}


	/**
	 * Set the value of title
	 *
	 * @access public
	 * @param string $value The value to set to title
	 * @return Person The object instance for method chaining
	 */
	public function setTitle($value)
	{
		$this->title = $value;

		return $this;
	}


    /**
     * Get the value of profession
     *
     * @access public
     * @return Profession The value of profession
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set the value of profession
     *
     * @access public
     * @param Profession $value The value to set to profession
     * @return Person The object instance for method chaining
     */
    public function setProfession(Profession $value = null)
    {
        $this->profession = $value;

        return $this;
    }

    /**
     * Get the value of professionId
     *
     * @access public
     * @return integer The value of professionId
     */
    public function getProfessionId()
    {
        return $this->professionId;
    }

    /**
     * Set the value of professionId
     *
     * @access public
     * @param integer $value The value to set to professionId
     * @return Person The object instance for method chaining
     */
    public function setProfessionId($value)
    {
        $this->professionId = $value;

        return $this;
    }


    /**
     *
     */
    public function setSuiteRxPersonId($suiteRxId)
    {
        if ($suiteRxId) {
            $this->suiteRxId = $suiteRxId;
        }

        return $this;
    }

}
