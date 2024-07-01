<?php

/**
 *
 */
class Questions extends Repository
{
    static protected $entity = 'Question';

    static protected $order = [
        'displayOrder' => 'ASC'
    ];


    /**
     *
     */
    public function findForCart(Gloudemans\Shoppingcart\Cart $cart)
    {
        $product_ids = array();

        foreach ($cart->content() as $item) {
            $product_ids[] = $item->id;
        }

        return $this->query(function($query) use ($product_ids) {
            $query
                ->join('this.products', 'product')
                ->where('product.id IN(?1)')
                ->andWhere('this.active = 1')
                ->setParameter(1, $product_ids)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function reset()
    {
        $this->getEntityManager()->getConnection()->exec('DELETE FROM questions');
        $this->getEntityManager()->getConnection()->exec('ALTER TABLE questions AUTO_INCREMENT = 0');
    }
}
