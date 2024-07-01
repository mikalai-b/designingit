<?php

namespace Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

class ReplaceQueryStringParameter extends Twig_Extension
{

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('replace_query_string_parameter', function($url, $attributes, $replacements = null){
				$queryString = '';
				if (is_string($attributes)) {
					$attributes = [$attributes];
				}
				if (is_string($replacements)) {
					$replacements = [$replacements];
				}
                if (preg_match('/^(.*)\?(.*)$/', $url, $matches)) {
					$url = $matches[1];
					$queryString = $matches[2];
				}				
				parse_str($queryString, $parameters);
				foreach ($attributes as $key => $attribute) {
					if (isset($parameters[$attribute])) {
						unset($parameters[$attribute]);
					}
					if (isset($replacements[$key])) {
						$parameters[$attribute] = $replacements[$key];
					} 
				}
				$url .= '?'.http_build_query($parameters);
				return $url;
			})
        ];
    }

}