<?php

namespace App\Http\Actions;

use Cassandra\Date;
use Prescriptions;
use LineItems;
use Products;
use Consultations;
use Checkout;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;

/**
 *
 */
class ViewPrescription extends AbstractAction
{
    /**
     *
     * @param Prescriptions $prescriptions
     * @param string $id
     */
    public function __invoke(Prescriptions $prescriptions, Checkout $checkout, $id)
    {
        $items  = array();
        $person = $this->auth->user();
        $action = $this->request->input('action', 'view');
        $cards  = $checkout->getCards($person);

        if (!$prescription = $prescriptions->find($id)) {
            return $this->respond(NULL, 404);
        }

        $this->authorize($action, $prescription);

        return $this->render('pages.prescriptions.select', 200, get_defined_vars());
    }

    public function updateSinglePrescription(Request $request, int $prescriptionId)
    {
        $productRepository = app()->make(Products::class);
        $prescriptionRepository = app()->make(Prescriptions::class);
        $lineItemsRepository = app()->make(LineItems::class);

        $prescription = $prescriptionRepository->findOneBy(['id' => $prescriptionId]);
        $product = $productRepository->find($request->productId);

        $prescription->setInstructions($product->getInfo());
        $prescriptionRepository->store($prescription, true);

        $lineItem = $lineItemsRepository->findOneBy(['id' => $prescription->getLineItem()->getId()]);
        $lineItem->setProduct($product);
        $lineItemsRepository->store($lineItem, true);

        return ['result' => true, 'message' => 'Prescription updated!'];
    }

    public function updatePrescriptionSetStatus(Request $request)
    {
        $prescriptionRepository = app()->make(Prescriptions::class);
        $prescriptions = $prescriptionRepository->findBy(['consultation' => (int)$request->consultationId]);

        $previousEndDate = null;
        foreach ($prescriptions as $prescription) {
            if ($request->action === 'cancel') {
                if ($prescription->getStatus() === 'Active' || $prescription->getStatus() === 'Paused') {
                    $prescription->setStatus('Canceled');
                }
            }

            if ($request->action === 'pause') {
                if ($prescription->getStatus() === 'Active') {
                    $prescription->setStatus('Paused');
                }
            }

            if ($request->action === 'resume' && $prescription->getStatus() === 'Paused') {
                $currentDate = new DateTime();
                $currentDate->setTime(0, 0, 0);

                $dateEnd = $prescription->getDateEnd();
                $dateEnd->setTime(0, 0, 0);

                // if ($currentDate > $dateEnd) {
                //     continue;
                // }

                if ($previousEndDate === null) {
                    // First prescription in the loop
                    if ($dateEnd <= $currentDate) {
                        $newDateEnd = new DateTime('tomorrow');
                    } else {
                        $newDateEnd = $prescription->getDateEnd();
                    }
                } else {
                    // Subsequent prescriptions in the loop
                    $newDateEnd = $previousEndDate->add(new DateInterval('P28D'));
                }

                $prescription->setDateEnd($newDateEnd);
                $prescription->setStatus('Active');

                // Update previousEndDate for the next iteration
                $previousEndDate = clone $newDateEnd;
            }
        }

        return ['result' => true, 'message' => 'Prescription set status updated!'];
    }
}
