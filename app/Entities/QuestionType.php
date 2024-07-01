<?php

/**
 *
 */
class QuestionType extends Base\QuestionType
{
    /**
     * Instantiate a new QuestionType
     */
    public function __construct()
    {
        return parent::__construct();
    }


    /**
     *
     */
    public function requireNote()
    {
        return in_array($this->getTemplate(), [
            'yes-no-note'
        ]);
    }
}
