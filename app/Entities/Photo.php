<?php

/**
 *
 */
class Photo extends Base\Photo
{

    /**
     * Instantiate a new Photo
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        return parent::__construct();
    }

    public function getS3Url()
    {
        $s3 = App::make('Extensions\Twig\S3');
        if ($s3) {
            return $s3->url($this->getFile());
        }
    }

}
