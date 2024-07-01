<?php

namespace Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

class DaysToMonths extends Twig_Extension
{

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('days_to_months', function($v){
				$v = intval($v / 30);
				return sprintf('%s month%s', (($v !== 1) ? $v : null), ($v !== 1 ? 's' : null));
			})
        ];
    }

}