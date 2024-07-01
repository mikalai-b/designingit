<?php

use App\Services\Cart;

/**
 *
 */
class PhotoTypes extends Repository
{
    /**
     *
     */
    static protected $entity = 'PhotoType';


    /**
     *
     */
    static protected $order = ['displayOrder' => 'asc'];


    /**
     *
     */
    public function findForCart(Cart $cart)
    {
        $product_ids = array();

        foreach ($cart->content() as $item) {
            $product_ids[] = $item->id;
        }

        return $this->query(function($query) use ($product_ids) {
            $query
                ->join('this.products', 'product')
                ->where('product.id IN(?1)')
                ->setParameter(1, $product_ids)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findForConsultation(Consultation $consultation)
    {
        return $this->findById($consultation->getPhotos()->map(function($photo) {
            return $photo->getType();
        }));
    }
}
