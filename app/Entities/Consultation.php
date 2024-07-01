<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
class Consultation extends Base\Consultation
{
    const STATUS_NEW        = 'Started';       // Newly created
    const STATUS_OPEN       = 'Open';      // Completed by a user
    const STATUS_PENDING    = 'Pending';   // Reviewed by Provider
    const STATUS_COMPLETED  = 'Completed'; // Action Taken


    /**
     * Instantiate a new Consultation
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        return (string) $this->getOrder();
    }


    /**
     *
     */
    public function getApprovedProducts()
    {
        return $this->getPrescriptions()->map(function($prescription) {
            return $prescription->getLineItem()->getProduct();
        });
    }


    /**
     *
     */
    public function getDeclinedProducts()
    {
        return $this->getProducts()->filter(function($product) {
            return !$this->getApprovedProducts()->contains($product);
        });
    }


    /**
     *
     */
    public function getDefaultLetter()
    {

    }


    /**
     *
     */
    public function getPhotoTypes()
    {
        $photos  = $this->getPhotos();
        $types   = array();

        foreach ($photos as $photo) {
            if (in_array($photo->getType(), $types, TRUE)) {
                continue;
            }

            $types[] = $photo->getType();
        }

        return $types;
    }


    /**
     *
     */
    public function getPhotoTypesForProducts()
    {
        $types   = array();

        foreach ($this->getOrder()->getLineItems() as $lineItem) {
            foreach ($lineItem->getProduct()->getPhotoTypes() as $photoType) {
                $types[$photoType->getId()] = $photoType;
            }
        }

        return array_values($types);
    }


    /**
     *
     */
    public function getPhotosForType(PhotoType $type)
    {
        return $this->getPhotos()->filter(function($photo) use ($type) {
            return $photo->getType() === $type;
        });
    }


    /**
     *
     */
    public function getProducts()
    {
        return $this->getOrder()->getProducts();
    }


    /**
     *
     */
    public function isCompleted()
    {
        return $this->getStatus() == static::STATUS_COMPLETED;
    }


    /**
     *
     */
    public function isOpen()
    {
        return $this->getStatus() == static::STATUS_OPEN;
    }


    /**
     *
     */
    public function isPending()
    {
        return $this->getStatus() == static::STATUS_PENDING;
    }


    /**
     * 
     */
    public function isDeclined()
    {
        return $this->isCompleted() && !count($this->getPrescriptions());
    }


    /**
     * 
     */
    public function getStatusWithResult()
    {
        if ($this->isCompleted()) {
            $result = $this->isDeclined() ? 'Declined' : 'Prescribed';
            return $this->getStatus() . ': ' . $result;
        }
        return $this->getStatus();
    }


    /**
     *
     */
    public function setCompleted()
    {
        $this->setStatus(static::STATUS_COMPLETED);
    }


    /**
     *
     */
    public function setOpen()
    {
        $this->setStatus(static::STATUS_OPEN);
    }


    /**
     *
     */
    public function setPending()
    {
        $this->setStatus(static::STATUS_PENDING);
    }


    /**
     *
     */
    public function removePhotos(traversable $photos)
    {
        foreach ($photos as $photo) {
            if ($this->getPhotos()->contains($photo)) {
                $this->getPhotos()->removeElement($photo);
            }
        }
    }
}
