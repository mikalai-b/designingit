<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Extensions\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Cache\CacheManager;


/**
 * Access Laravels url class in your Twig templates.
 */
class S3 extends Twig_Extension
{
    /**
     *
     */
    public function __construct(Config $config, CacheManager $cache, FilesystemManager $fs)
    {
        $this->config = $config;
        $this->cache  = $cache;
        $this->fs     = $fs;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Extension_Twig_S3';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('s3url', [$this, 'url'], ['is_safe' => ['html']])
        ];
    }


    public function url($path = null, $parameters = [], $secure = null)
    {
        $key = sprintf('%s:%s', static::class, $path);
        $s3  = $this->fs->disk('s3');

        if (!$this->cache->has($key)) {
            $expiry  = 10;
            $client  = $s3->getDriver()->getAdapter()->getClient();
            $command = $client->getCommand('GetObject', [
                'Bucket' => $this->config->get('filesystems.disks.s3.bucket'),
                'Key'    => $path
            ]);

            $request = $client->createPresignedRequest($command, '+' . $expiry . ' minutes');

            $this->cache->put($key, (string) $request->getUri(), $expiry - 1);
        }

        return $this->cache->get($key);
    }
}
