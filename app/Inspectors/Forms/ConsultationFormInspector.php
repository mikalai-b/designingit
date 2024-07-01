<?php

/**
 *
 */
class ConsultationFormInspector extends AbstractInspector
{
    static protected $rules = [
    ];


    /**
     *
     */
    public function validate($data, Consultation $consultation = NULL)
    {
        if ($consultation) {
            foreach ($consultation->getAnswers() as $answer) {
                if (!isset($data['answers'][$answer->getKey()])) {
                    $this->messages->add('answer-' . $answer->getKey(), 'Please provide an answer to this question');
                } else {
                    if ($answer->getType()->requireNote()) {
                        if ($answer->getContent() == '1') {
                            if (!isset($data['notes'][$answer->getKey()])) {
                                $this->messages->add('answer-' . $answer->getKey(), 'Please provide additional details');
                            }
                        }
                    }
                }
            }
        }
    }
}
