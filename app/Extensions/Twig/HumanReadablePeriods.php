<?php

namespace Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

class HumanReadablePeriods extends Twig_Extension
{

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('human_readable_periods', function($v){
                $joined = join(', ', collect($v)->map(function($periodInDays) {
                    return $this->daysToMonths($periodInDays);
                })->toArray());

                return preg_replace('/(.*), ([^ ]+)$/', '$1 and $2', $joined)  . ' months';
			})
        ];
    }

    private function daysToMonths($v)
    {
        return intval($v / 30);
    }

}