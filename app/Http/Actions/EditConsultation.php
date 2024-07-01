<?php

namespace App\Http\Actions;

use Consultations;
use Prescriptions;
use Rx;

use RuntimeException;
use App\Exceptions\ValidationException;
use SuiteRx;
use Symfony\Component\HttpFoundation\Response;
use Products;
use LineItems;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class EditConsultation extends AbstractAction
{
    /**
     * @var string
     */
    const MSG_SUCCESS_COMPLETE = 'Congratulations, you have successfully completed that consultation.';

    /**
     * @param Consultations $consultations The consultations repository
     * @param Prescriptions $prescriptions The Prescriptions repository
     * @param Rx $rx The Rx (Prescription) Service
     * @param SuiteRx $suitRx  SuiteRx (Prescription) Service
     * @param string $id The id of the consultation as retrieved from URL
     * @return Response A response object containing response information
     */
    public function __invoke(Consultations $consultations, Prescriptions $prescriptions,SuiteRx $suiteRx, Rx $rx, $id, Products $productsRepository, LineItems $lineItems, EntityManagerInterface $entityManager)
    {
        $action = $this->request->input('action', 'edit');
        $user   = $this->auth->user();
        $products = $productsRepository->findAll(['name'=>'asc']);

        if (!$consultation = $consultations->find($id)) {
            return $this->respond(NULL, 404);
        }

        // If medication changed - update exist order line item info
        $order = $consultation->getOrder();
        $product = $order->getProducts()->first();
        if ((string)$product->getId() !== $this->request->input('medication')) {
            $other_selected_product = $productsRepository->findOneBy(['id' => $this->request->input('medication')]);
            $order_line_items = $order->getLineItems()->first();

            if ($other_selected_product && $order_line_items) {
                $order_line_items->setPeriod($other_selected_product->getAvailableDashboardPeriods()[2]);
                $order_line_items->setProduct($other_selected_product);
                $order_line_items->setPrice($other_selected_product->getPrice());
                $order_line_items->setFirstShipmentPrice($other_selected_product->getPrice());
                $order_line_items->setSecondShipmentPrice($other_selected_product->getPrice());
                $entityManager->flush();
            }
        }


        $this->authorize($action, $consultation);

        try {
            if ($action == 'complete') {
                $items         = $this->request->input('items', []);
                $symptoms      = $this->request->input('symptoms', []);
                $letter        = $this->request->input('letter', NULL);
                $diagnosis     = $this->request->input('diagnosis', array());
                $physical_exam = $this->request->input('physicalExam', array());

                $consultation->setLetter($letter);
                $consultation->setDiagnosis($diagnosis);
                $consultation->setPhysicalExam($physical_exam);
                $consultation->setSymptoms($consultations->getSymptoms()->findById($symptoms));


                $selectedProduct = $consultation->getOrder()->getProducts()->first();
                if ($selectedProduct->getCategory()->getSlug() === 'derma') {
                    $productPrescriptionVariants = $productsRepository->findBy(['name' => $selectedProduct->getName()]);
                }

                foreach ($consultation->getOrder()->getLineItems() as $line_item) {
                    if (empty($items[$line_item->getId()]['approved'])) {
                        continue;
                    }

                    $consultation->getPrescriptions()->add($prescriptions->createFromLineItem(
                        $line_item,
                        $items[$line_item->getId()]['refills'],
                        $items[$line_item->getId()]['directions'],
                        $items[$line_item->getId()]['expires']
                    ));
                }

                $selectedProduct = $consultation->getOrder()->getProducts()->first();
                $consultations->inspect($consultation);

                if ($selectedProduct->getCategory()->getSlug() === "derma") {
                    $suiteRx->createFromConsultation($consultation);
                } else {
                    $rx->createFromConsultation($consultation);
                }

                $this->session->flash('success', static::MSG_SUCCESS_COMPLETE);

                return $this->redirect('dashboard', 303);
            }

        } catch (ValidationException $e) {
            $errors = $e->getMessages();
            $this->session->flash('error', $e->getMessage());

            return $this->render('pages.consultations.select', 400, get_defined_vars());

        } catch (RuntimeException $e) {
            $this->session->flash('error', sprintf(
                'We were unable to complete the consultation at this time. '.$e->getMessage().' Check your e-mail for details.'
            ));

            return $this->render('pages.consultations.select', 400, get_defined_vars());
        }

        return $this->render('pages.consultations.select', 200, get_defined_vars());
    }
}
