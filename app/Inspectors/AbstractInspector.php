<?php

use Doctrine\ORM\EntityManagerInterface;

use Illuminate\Support\MessageBag;

use Illuminate\Validation;
use App\Exceptions\ValidationException;
use Illuminate\Auth\AuthManager;

/**
 *
 */
abstract class AbstractInspector
{
    const MSG_VALIDATION_FAILURE = 'Please correct the errors shown below.';

    /**
     *
     */
    static protected $errors = [
        'required' => 'This field is required',
    ];


    /**
     *
     */
    static protected $rules = [

    ];


    /**
     *
     */
    protected $auth = NULL;


    /**
     *
     */
    protected $em = NULL;


    /**
     *
     */
    protected $messages = NULL;


    /**
     *
     */
    protected $validator = NULL;


    /**
     *
     */
    protected $validatorFactory = NULL;


    /**
     *
     */
    public function __construct(Validation\Factory $validator_factory, MessageBag $messages, EntityManagerInterface $em, AuthManager $auth)
    {
        $this->validatorFactory = $validator_factory;
        $this->messages         = $messages;
        $this->auth             = $auth;
        $this->em               = $em;
    }


    /**
     *
     */
    public function getValidator()
    {
        return $this->validator;
    }


    /**
     *
     */
    public function getMessages()
    {
        return $this->messages;
    }


    /**
     *
     */
    public function run($data, ...$context)
    {
        if (is_array($data)) {
            $this->validator = $this->validatorFactory->make($data, static::$rules, static::$errors);

        } elseif ($data instanceof Entity) {
            $this->validator = $this->validatorFactory->make($data->toArray(), static::$rules, static::$errors);

        } else {

            //
            // TODO: eh?
            //
        }

        $this->validate($data, ...$context);

        try {
            $this->validator->validate();

            if (count($this->messages)) {
                throw new Validation\ValidationException('There are custom validation errors.');
            }

        } catch (Validation\ValidationException $e) {
            $exception = new ValidationException(static::MSG_VALIDATION_FAILURE, 0, $e);

            $this->messages->merge($this->validator->getMessageBag());

            $exception->setMessages($this->messages);

            throw $exception;
        }
    }


    /**
     *
     */
    abstract protected function validate($data);
}
