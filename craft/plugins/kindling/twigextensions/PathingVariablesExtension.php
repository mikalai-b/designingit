<?php
namespace Craft;

use Twig_Extension;
use Twig_Extension_GlobalsInterface;
use Twig_SimpleFunction;

class PathingVariablesExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    /**
     * Returns an array of the path segments, sanitized.
     *
     * @param $offset int
     *     identical to array_slice(), the starting offset. Can be negative.
     * @param $length int
     *     identical to array_slice(), the number of segements to return. Like
     *     array_slice, it can be negative to indicate a trailing offset
     *     instead of a length.
     *
     * @return array
     */
    public function getPathSegments($offset = 0, $length = null)
    {
        $segments = array_filter(explode('/', craft()->request->path));
        $segments = array_slice($segments, $offset, $length);

        return array_map(
            function($segment) {
                return strtolower(preg_replace('([^\w-])', '', $segment));
            },
            $segments
        );
    }

    /**
     * Returns a string of the path segments, sanitized and joined with spaces.
     *
     * @param $offset int
     *     identical to array_slice(), the starting offset. Can be negative.
     * @param $length int
     *     identical to array_slice(), the number of segements to return. Like
     *     array_slice, it can be negative to indicate a trailing offset
     *     instead of a length.
     *
     * @return string
     */
    public function getPathGeneratedClasses($offset = 0, $length = null)
    {
        return implode(' ', $this->getPathSegments($offset, $length));
    }

    /**
     * Returns a string of the path segments, sanitized and joined with dashes.
     *
     * @param $offset int
     *     identical to array_slice(), the starting offset. Can be negative.
     * @param $length int
     *     identical to array_slice(), the number of segements to return. Like
     *     array_slice, it can be negative to indicate a trailing offset
     *     instead of a length.
     *
     * @return string
     */
    public function getPathGeneratedID($offset = 0, $length = null)
    {
        return implode('-', $this->getPathSegments($offset, $length));
    }

    public function getName()
    {
        return 'Kindling Path Generated Variables';
    }

    /**
     * Global Twig variables defined by this extension.
     *
     * @return array
     */
    public function getGlobals()
    {
        return [
            'path_classes' => $this->getPathSegments(),
            'path_id'      => $this->getPathGeneratedID(),
        ];
    }

    /**
     * Twig functions defined by this extension.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('path_classes', [$this, 'getPathGeneratedClasses']),
            new Twig_SimpleFunction('path_id', [$this, 'getPathGeneratedID']),
        ];
    }
}
