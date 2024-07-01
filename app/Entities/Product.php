<?php

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Resources\Json\Resource;
use Gloudemans\Shoppingcart\Contracts\Buyable;

/**
 *
 */
class Product extends Base\Product implements Buyable
{
    /**
     * 
     */
    protected $overridePrice;

    /**
     * Instantiate a new Product
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
        return sprintf('%s (%s %s)', $this->getName(), $this->getType(), $this->getStrength());
    }

    
    /**
     *
     */
    public function getCustomerFacingName()
    {

        if ($this->getType() && strpos($this->getType()->getName(), 'Tretinoin') !== false) {
            return $this->getType()->getName();
        } elseif (strpos($this->getName(), 'Latisse') !== false) {
            return $this->getName();
        }
        return $this->getName();
    }


    /**
     *
     */
    public function toArray()
    {
        return parent::toArray() + ['toString'=>$this->__toString()];
    }



    /**
     *
     */
    public function checkConsent(array $consent)
    {
        return !empty($consent[(string) $this->getId()]);
    }

    /**
     *
     */
    public function checkPeriod(array $periods, $period = NULL)
    {
        if (!array_key_exists((string) $this->getId(), $periods)) {
            return NULL;
        }

        return $periods[(string) $this->getId()] == $period;
    }



    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return (string) $this->getId();
    }


    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->getCustomerFacingName();
    }


    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null)
    {
        return $this->overridePrice ?: $this->getPrice();
    }


    /**
     *
     */
    public function getDefaultRefills()
    {
        return parent::getDefaultRefills() ?: $this->getType()->getDefaultRefills();
    }


    /**
     *
     */
    public function getDefaultExpiration()
    {
        return parent::getDefaultExpiration() ?: $this->getType()->getDefaultExpiration();
    }


    /**
     *
     */
    public function getAvailablePeriods()
    {
        return parent::getAvailablePeriods() ?: $this->getType()->getAvailablePeriods();
    }


    /**
     *
     */
    public function getAvailableDashboardPeriods()
    {
        return parent::getAvailableDashboardPeriods() ?: $this->getType()->getAvailableDashboardPeriods();
    }


    /**
     *
     */
    public function getDefaultAutoRenewal()
    {
        return parent::getDefaultAutoRenewal() ?: $this->getType()->getDefaultAutoRenewal();
    }


    /**
     *
     */
    public function getRequireAutoRenewal()
    {
        return parent::getRequireAutoRenewal() ?: $this->getType()->getRequireAutoRenewal();
    }


    /**
     *
     */
    public function getHasOnePeriod()
    {
        return count($this->getAvailablePeriods()) === 1;
    }


    /**
     *
     */
    public function getFirstPeriod()
    {
        return $this->getAvailablePeriods()[0] ?? null;
    }

    /**
     * 
     */
    public function getFirstPeriodInMonths()
    {
        $period = $this->getFirstPeriod();
        return intval($period / 30);
    }


    /**
     * 
     */
    public function getDefaultPeriod()
    {
        return parent::getDefaultPeriod() ?: $this->getType()->getDefaultPeriod();
    }

    /**
     * 
     */
    public function getDefaultPeriodInMonths()
    {
        $period = $this->getDefaultPeriod();
        return intval($period / 30);
    }


    /**
     * @return string
     */
    public function getFriendlyDefaultPeriod()
    {
        $months = $this->getDefaultPeriodInMonths();
        if ($months === 1) {
            return 'month';
        }
        return sprintf('%s months', spell_out_int($months));
    }
    

    /**
     * 
     */
    public function getIsAssociatedWithACouponCode()
    {
        return count($this->getCouponCodes()) > 0;
    }

    /**
     * 
     */
    public function setOverridePrice($v)
    {
        $this->overridePrice = $v;
    }

    /**
     * 
     */
    public function addQuestion(Question $question)
    {
        if ($this->questions->contains($question)) {
            return;
        }
        $this->questions->add($question);
    }

    /**
     * 
     */
    public function isEligibleForGroupon()
    {
        $em = app()->make(EntityManager::class);
        $stmt = $em->getConnection()
            ->prepare("SELECT COUNT(*) FROM product_coupon_codes WHERE product = ?");
        $stmt->execute([$this->getId()]);
        $count = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];
        return $count > 0;
    }

    /**
     * 
     */
    public function addCouponCodes(Collection $codes = null)
    {
        if ($codes) {
            foreach ($codes as $code) {
                $code->addProduct($this);
            }
        }
    }
}
