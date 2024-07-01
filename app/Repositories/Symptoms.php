<?php

/**
 *
 */
class Symptoms extends Repository
{
    /**
     *
     */
    static protected $entity = 'Symptom';


    /**
     *
     */
    static protected $order = ['content' => 'ASC'];


    /**
     *
     */
    public function findForConsultation(Consultation $consultation)
    {
        $product_types = $consultation->getOrder()->getLineItems()->map(function($item) {
            return $item->getProduct()->getType();
        });

        return $this->collect($this->query(function($builder) use ($product_types) {
            $builder
                ->where('productType IN(?1)')
                ->leftJoin('this.productTypes', 'productType')
                ->setParameter(1, $product_types)
            ;
        }));
    }
}
