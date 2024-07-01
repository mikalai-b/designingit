<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\MountManager;
use Doctrine\Common\EventArgs;

class PhotosListener
{
    /**
     *
     */
    public function __construct(FilesystemManager $fs, MountManager $mount, Photos $photos)
    {
        $this->fs     = $fs;
        $this->mount  = $mount;
        $this->photos = $photos;
    }



    /**
     *
     */
    public function prePersist(Photo $photo, EventArgs $args)
    {
        $this->moveToS3($photo);
    }

    /**
     *
     */
    public function preUpdate(Photo $photo, EventArgs $args)
    {
        $this->moveToS3($photo);
    }


    /**
     *
     */
    public function postRemove(Photo $photo, EventArgs $args)
    {
        $this->removeFromS3($photo);
    }


    /**
     *
     */
    protected function mount()
    {
        $this->mount->mountFilesystem('s3', $this->fs->disk('s3')->getDriver());
        $this->mount->mountFilesystem('root', $this->fs->disk('root')->getDriver());
    }


    /**
     *
     */
    protected function moveToS3($photo)
    {
        if ($photo->getFile() instanceof UploadedFile) {
            $file      = $photo->getFile();
            $extension = $file->getClientOriginalExtension();
            $basename  = uniqid(md5($file->getClientOriginalName()), TRUE);
            $filename  = sprintf('%s.%s', $basename, $extension);
            $target    = sprintf('%s/photos/%s', getenv('APP_ENV'), $filename);

            $this->mount();
            $this->mount->copy('root://' . $photo->getFile()->path(), 's3://'   . $target);

            $photo->setFile($target);
        }
    }


    /**
     *
     */
    protected function removeFromS3($photo)
    {
        $this->fs->disk('s3')->delete($photo->getFile());
    }
}
