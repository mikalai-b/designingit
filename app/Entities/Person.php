<?php

use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Authenticatable;

use App\Notifications\PasswordReset as PasswordResetNotification;

/**
 *
 */
class Person extends Base\Person implements Authenticatable, CanResetPasswordContract, HasRolesContract, HasPermissionContract
{
    use Notifiable;
    use CanResetPassword;
    use HasPermissions;
    use HasRoles;


    /**
     * Instantiate a new Person
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        return preg_replace('/\s+/', ' ', trim(sprintf(
            '%s %s %s %s, %s',
            $this->getTitle(),
            $this->getFirstName(),
            $this->getMiddleName(),
            $this->getLastName(),
            $this->getCredentials()
        ), ', ')) ?: 'Unknown Name';
    }


    /**
     *
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }


    /**
    *
    */
    public function getAuthIdentifierName()
    {
        return 'id';
    }


    /**
     *
     */
    public function getAuthPassword()
    {
        if (!$this->getAccount()) {
            return NULL;
        }

        return $this->getAccount()->getPassword();
    }


    /**
     *
     */
    public function getConsultations()
    {
        return $this->getOrders()->map(function($order) {
            return $order->getConsultation();
        });
    }


    /**
     *
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }


    /**
    *
    */
    public function getPermissions()
    {
        return $this->getAccount()
            ? $this->getAccount()->getPermissions()
            : new ArrayCollection();
    }


    /**
     *
     */
    public function getReceipt(Message $message)
    {
        foreach ($message->getReceipts() as $receipt) {
            if ($receipt->getRecipient() === $this) {
                return $receipt;
            }
        }

        return NULL;
    }


    /**
     *
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }


    /**
     *
     */
    public function getRoles()
    {
        return $this->getAccount()
            ? $this->getAccount()->getRoles()
            : new ArrayCollection();
    }


    /**
     *
     * @param State $state The state to check
     */
    public function isState(State $state, State $default = NULL)
    {
        if ($this->getState()) {
            return $state === $this->getState();
        } elseif ($default) {
            return $state === $default;
        } else {
            return FALSE;
        }
    }


    /**
     * Get the postalCode without non-digits
     *
     * @access public
     * @return string
     */
    public function getTrimmedPostalCode()
    {
        return preg_replace('/\D/', '', $this->postalCode);
    }


    /**
     *
     */
    public function toEmail()
    {
        return [$this->getFullName() => $this->getEmail()];
    }

    /**
     * 
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }


    /**
     * 
     */
    public function shouldReceiveChatNotifications()
    {
        return !$this->hasRoleByName('Provider');
    }

}
