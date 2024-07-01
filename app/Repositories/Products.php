<?php

use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *

 */
class Products extends Repository
{
    static protected $entity = 'Product';


    /**
     *
     */
    public function __construct(EntityManager $em, ProductTypes $types)
    {
        $this->types = $types;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function findSuggestions(Cart $cart)
    {
        return $this->collect($this->query(function($builder) use ($cart) {
            $builder
                ->where('this.type NOT IN (?1)')
                ->setParameter(1, $this->findForCart($cart)->map(function($product) {
                    return $product->getType();
                }))
            ;
        }));
    }


    /**
     *
     */
    public function findForCart(Cart $cart)
    {
        $existing_ids = array();

        foreach ($cart->content() as $item) {
            $existing_ids[] = (string) $item->id;
        }

        return $this->findById($existing_ids);
    }


    /**
     *
     */
    public function getTypes()
    {
        return $this->types;
    }
}
