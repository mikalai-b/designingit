<?php
/**
 * @copyright 2017 iMarc LLC
 * @license Apache (see LICENSE file)
 */

namespace Craft;

/**
 * Helpers
 */
class RxPlugin extends BasePlugin
{
    protected $allowAnonymous = true;

    public function getName()
    {
        return Craft::t('Rx Helpers');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Imarc';
    }

    public function getDeveloperUrl()
    {
        return 'https://www.imarc.com';
    }
}