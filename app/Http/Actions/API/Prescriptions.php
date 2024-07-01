<?php

namespace App\Http\Actions\API;

use Prescriptions as PrescriptionsRepository;

use App\Http\Actions\AbstractAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Prescription;

/**
 *
 */
class Prescriptions extends AbstractAction
{
    protected $prescriptions;

    /**
     *
     */
    public function __invoke()
    {
    }

    /**
     * 
     */
    public function updateRefillFrequency($id, Request $request, PrescriptionsRepository $prescriptions)
    {
        $prescription = $prescriptions->find($id);
        $this->authorize('edit', $prescription);
        $prescription->getLineItem()->setPeriod($request->input('period'));
        $this->session->flash('success', 'Refill frequency has been updated for this prescription.');

        return response()->json([
            'success' => true,
            'redirect' => URL::previous()
        ]);
    }

    /**
     * 
     */
    public function pause($id, PrescriptionsRepository $prescriptions)
    {
        $prescription = $prescriptions->find($id);
        $this->authorize('edit', $prescription);
        $prescription->setStatus(Prescription::STATUS_PAUSED);
        $this->session->flash('success', 'Refills have been paused for this prescription.');

        return response()->json([
            'success' => true,
            'redirect' => URL::previous()
        ]);
    }

    /**
     * 
     */
    public function resume($id, PrescriptionsRepository $prescriptions)
    {
        $prescription = $prescriptions->find($id);
        $this->authorize('edit', $prescription);
        $prescription->setStatus(Prescription::STATUS_ACTIVE);
        $this->session->flash('success', 'Refills have been resumed for this prescription.');

        return response()->json([
            'success' => true,
            'redirect' => URL::previous()
        ]);
    }

    /**
     * 
     */
    public function cancel($id, PrescriptionsRepository $prescriptions)
    {
        $prescription = $prescriptions->find($id);
        $this->authorize('edit', $prescription);
        $prescription->setStatus(Prescription::STATUS_CANCELED);
        $this->session->flash('success', 'Refills for this prescription have been canceled.');

        return response()->json([
            'success' => true,
            'redirect' => URL::previous()
        ]);
    }
}