<?php

namespace App\Console\Commands;

use Account;
use Accounts;
use Collection;
use Consultations;
use DateTime;
use Illuminate\Console\Command;
use LineItems;
use Order;
use Orders;
use People;
use Person;
use Prescriptions;
use Products;
use States;

class CreateTestAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:create-test-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var People
     */
    protected $people;

    /**
     * @var States
     */
    protected $states;

    /**
     * @var Accounts
     */
    protected $accounts;

    /**
     * @var Orders
     */
    protected $orders;

    /**
     * @var Products
     */
    protected $products;

    /**
     * @var LineItems
     */
    protected $lineItems;

    /**
     * @var Consultations
     */
    protected $consultations;

    /**
     * @var Prescriptions
     */
    protected $prescriptions;

    const PERSON_DATA = [
        'FirstName' => 'Test',
        'LastName' => 'User',
        'Gender' => 'F',
        'DateOfBirth' => '1980-01-01',
        'Email' => 'user@user.com',
        'Phone' => '234-234-2345',
        'AddressLine1' => '123 Test Lane',
        'City' => 'Testcity',
        'PostalCode' => '12345',
        'State' => 'CA',
    ];

    const ACCOUNT_DATA = [
        'Password' => 'buildingblocks',
    ];

    const ORDER_DATA = [
        'Status' => Order::STATUS_OPEN,
    ];

    const LINE_ITEM_DATA = [
        'Product' => '6f57b2fa-9251-4096-ba46-81dee92d5b18',
        'Period' => 90,
        'Price' => 159,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(People $people, States $states, Accounts $accounts, Orders $orders, Products $products, LineItems $lineItems, Prescriptions $prescriptions, Consultations $consultations)
    {
        $this->people = $people;
        $this->states = $states;
        $this->accounts = $accounts;
        $this->orders = $orders;
        $this->products = $products;
        $this->lineItems = $lineItems;
        $this->prescriptions = $prescriptions;
        $this->consultations = $consultations;
        $person = $this->createPerson();
        $account = $this->createAccount($person);
        $order = $this->createOrder($person);
    }

    /**
     *
     */
    public function createPerson()
    {
        $data = static::PERSON_DATA;
        $person = $this->people->create();
        $person->setState($this->states->find($data['State']));
        $person->setFirstName($data['FirstName']);
        $person->setLastName($data['LastName']);
        $person->setGender($data['Gender']);
        $person->setDateOfBirth(new DateTime($data['DateOfBirth']));
        $person->setEmail($data['Email']);
        $person->setPhone($data['Phone']);
        $person->setAddressLine1($data['AddressLine1']);
        $person->setCity($data['City']);
        $person->setPostalCode($data['PostalCode']);
        $this->people->store($person, true);
        return $person;
    }

    /**
     * 
     */
    private function createAccount(Person $person): Account
    {
        $data = static::ACCOUNT_DATA;
        $account = $this->accounts->create();
        $account->setPerson($person);
        $account->setPassword(password_hash($data['Password'], PASSWORD_BCRYPT));
        $this->accounts->store($account, true);
        return $account;
    }

    /**
     * 
     * @param Person $person
     * @return Account 
     */
    private function createOrder(Person $person): Order
    {
        $data = static::ORDER_DATA;
        $order = $this->orders->create();
        $order->setState($this->states->find(static::PERSON_DATA['State']));
        $order->setProvider($this->people->find(2));
        $order->setStatus($data['Status']);
        $this->orders->store($order, true);

        $lineItem = $this->lineItems->create();
        $lineItem->setProduct($this->products->find(static::LINE_ITEM_DATA['Product']));
        $lineItem->setPeriod(static::LINE_ITEM_DATA['Period']);
        $lineItem->setPrice(static::LINE_ITEM_DATA['Price']);
        $lineItem->setOrder($order);
        $this->lineItems->store($lineItem, true);

        $order->setLineItems(new Collection([$lineItem]));
        return $order;
    }
}
