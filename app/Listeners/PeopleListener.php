<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\MountManager;
use Doctrine\Common\EventArgs;

class PeopleListener
{
    /**
     *
     */
    public function __construct(FilesystemManager $fs, MountManager $mount, People $people)
    {
        $this->fs     = $fs;
        $this->mount  = $mount;
        $this->people = $people;
    }



    /**
     *
     */
    public function prePersist(Person $person, EventArgs $args)
    {
        $this->moveToS3($person);
    }

    /**
     *
     */
    public function preUpdate(Person $person, EventArgs $args)
    {
        $this->moveToS3($person);
    }


    /**
     *
     */
    public function postRemove(Person $person, EventArgs $args)
    {
        $this->removeFromS3($person);
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
    protected function moveToS3(Person $person)
    {
        if ($person->getAvatar() instanceof UploadedFile) {
            $file      = $person->getAvatar();
            $extension = $file->clientExtension();
            $basename  = uniqid(md5($file->getClientOriginalName()), TRUE);
            $filename  = sprintf('%s.%s', $basename, $extension);
            $target    = sprintf('%s/avatars/%s', getenv('APP_ENV'), $filename);

            $this->mount();
            $this->mount->copy('root://' . $person->getAvatar()->path(), 's3://'   . $target);

            $person->setAvatar($target);
        }
    }


    /**
     *
     */
    protected function removeFromS3(Person $person)
    {
        $this->fs->disk('s3')->delete($person->getAvatar());
    }
}
