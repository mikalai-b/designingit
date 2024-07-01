<?php

namespace App\Http\Actions;

use Illuminate\Contracts\Auth\Access\Gate;
use Products;

/**
 *
 */
class ManageProducts extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Products $products)
    {
        $action  = $this->request->query('action', 'manage');
        $product = $products->create();

        $this->authorize($action, $product);

        return $this->render(sprintf('pages.products.%s', $action), 200, get_defined_vars());
    }
}
