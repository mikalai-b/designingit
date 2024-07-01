<?php

namespace App\Http\Actions;

use Rx;
use Consultations;
use Products;
use Prescriptions;

class ViewConsultation extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Consultations $consultations, Rx $rx, $id, Products $productsRepository)
    {
        $items  = array();
        $action = $this->request->input('action', 'view');
        $user   = $this->auth->user();
        $products = $productsRepository->findAll(['name'=>'asc']);

        if (!$consultation = $consultations->find($id)) {
            return $this->respond(NULL, 404);
        }

        $selectedProduct = $consultation->getOrder()->getProducts()->first();
        if ($selectedProduct->getCategory()->getSlug() === 'derma') {
            $productPrescriptionVariants = $productsRepository->findBy(['name' => $selectedProduct->getName()]);
        }

        $this->authorize($action, $consultation);

        foreach ($consultation->getOrder()->getLineItems() as $line_item) {
            $id           = $line_item->getId();
            $product      = $line_item->getProduct();
            $product_type = $line_item->getProduct()->getType();
            $items[$id]   = [
                'approved'   => '1',
                'refills'    => $product->getDefaultRefills(),
                'directions' => $product_type->getDirections(),
                'expires'    => new \DateTime('+' . $product->getDefaultExpiration() . ' months'),
            ];
        }

        $prescriptionRepository = app()->make(Prescriptions::class);
        $prescriptions = $prescriptionRepository->findBy(['consultation' => $consultation->getId()]);
        if (count($prescriptions) > 1) {
            $prescriptionSetStatus = false;
            foreach ($prescriptions as $prescription) {
                if ($prescription->getStatus() === 'Paused') {
                    $prescriptionSetStatus = 'Paused';
                }
            }
        }

        return $this->render('pages.consultations.select', 200, get_defined_vars());
    }
}
