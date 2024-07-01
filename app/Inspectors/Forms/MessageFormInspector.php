<?php

/**
 *
 */
class MessageFormInspector extends AbstractInspector
{
    static protected $rules = [
        'body'      => 'required'
    ];


    /**
     *
     */
    public function validate($data)
    {
        if (isset($data['body']) && $data['body'] && !strip_tags($data['body'])) {
            $this->messages->add('body', 'This field is required');
        }

        if (empty($data['pid'])) {
            if (empty($data['subject'])) {
                $this->messages->add('subject', 'This field is required');
            }

            if (empty($data['recipient'])) {
                $this->messages->add('recipient', 'This field is required');
            }
        }
    }
}
