<?php

/**
 *
 */
class AddressFormInspector extends AbstractInspector
{
    static protected $rules = [
        'addressLine1' => 'required',
        'city'         => 'required',
        'state'        => 'required',
        'postalCode'   => 'required'
    ];


    /**
     *
     */
    public function validate($data)
    {

    }
}
