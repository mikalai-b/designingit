<?php

namespace Tests;

use Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Person;
use Provider;
use Role;
use Tests\Traits\RefreshDoctrineDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var Person $person
     */
    protected $person = null;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;

    public function setUp()
    {
        parent::setUp();
        $this->em = app()->make(EntityManager::class);
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();
        
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshDoctrineDatabase::class])) {
            $this->refreshDoctrineDatabase();
        }
    }

    protected function actingAsEndUser()
    {
        $person = entity(Person::class)->create();
        $account = entity(Account::class)->create([
            'person' => $person
        ]);
        $person->setAccount($account);
        $this->em->flush();
        $this->actingAs($person);
        $this->person = $person;
    }

    protected function createProvider(): Provider
    {
        try {
            $role = entity(Role::class)->create([
                'name' => 'Provider',
            ]);
            $person = entity(Person::class)->create();
            $account = entity(Account::class)->create([
                'person' => $person
            ]);
            $account->setRoles(new ArrayCollection([$role]));
            $person->setAccount($account);
            $this->em->flush();
            $provider = entity(Provider::class)->create([
                'person' => $person
            ]);
            $this->em->flush();
            return $provider;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    protected function actingAsPersonWithRole(string $role)
    {
        try {
            $role = entity(Role::class)->create([
                'name' => $role
            ]);
            $person = entity(Person::class)->create();
            $account = entity(Account::class)->create([
                'person' => $person
            ]);
            $account->setRoles(new ArrayCollection([$role]));
            $person->setAccount($account);
            $this->em->flush();
            $this->actingAs($person);
            $this->person = $person;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
