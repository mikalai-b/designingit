<?php

/**
 *
 * Message Keys:
 *  prescriptions
 */
class ConsultationInspector extends AbstractInspector
{
    static protected $rules = [

    ];


    /**
     *
     */
    public function validate($data)
    {
        if (!$data->getPhysicalExam()) {
            //
            // TODO: Yell
            //
        }

        if (!$data->getDiagnosis()) {
            //
            // TODO: Yell
            //
        }

        if ($data->isCompleted()) {
            foreach ($data->getPrescriptions() as $prescription) {
                if ($prescription->getId()) {
                    continue;
                }

                $this->messages->add('prescriptions', 'You cannot add new prescriptions to a closed consultation');
            }
        }
    }
}
