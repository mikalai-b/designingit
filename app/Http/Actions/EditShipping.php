<?php

namespace App\Http\Actions;

use People;
use States;
use AddressFormInspector;
use App\Exceptions\ValidationException;

/**
 *
 */
class EditAddress extends AbstractAction
{
    const MSG_SUCCESS = 'Your address has been successfully changed.';

    /**
     *
     */
    public function __invoke(People $people, States $states, AddressFormInspector $inspector)
    {
        $person = $this->auth->user();
        $input  = $this->request->all();

        if ($this->request->getMethod() == 'POST') {
            try {
                $person->setAddressLine1($input['addressLine1']);
                $person->setAddressLine2($input['addressLine2']);
                $person->setCity($input['city']);
                $person->setState($states->find($input['state']));
                $person->setPostalCode($input['postalCode']);

                $inspector->run($input);

                //
                // TODO: Update scripts?
                //

                $this->session->flash('success', static::MSG_SUCCESS);

                return $this->redirect('dashboard');

            } catch (ValidationException $e) {
                $errors = $inspector->getMessages();

                $this->session->flash('error', $e->getMessage());

                return $this->render('pages/dashboard/address', 400, get_defined_vars());
            }
        }

        return $this->render('pages/dashboard/address', 200, get_defined_vars());
    }
}
