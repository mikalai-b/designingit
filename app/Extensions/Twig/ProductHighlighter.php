<?php

namespace Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

class ProductHighlighter extends Twig_Extension
{

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('product_highlighter', function($v){
				$productNames = ['Latisse', 'Tretinoin'];
				foreach ($productNames as $productName) {
					$v = preg_replace('/('.$productName.')/i', '<em>$1</em>', $v);
				}
				return $v;
			})
        ];
    }

}