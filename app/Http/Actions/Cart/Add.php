<?php
namespace App\Http\Actions\Cart;

use Products;
use App\Exceptions;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use Checkout;
use Illuminate\Http\Request;
use Registration;

/**
 *
 */
class Add extends AbstractAction
{
    /**
     * @var string
     */
    const MSG_DUPLICATE = 'You have already added a product of this type to the cart.';


    /**
     * @var string
     */
    const MSG_NOT_FOUND = 'We could not find that product at this time. Please try again later.';


    /**
     * @var string
     */
    const MSG_SUCCESS   = '%s has been successfully added to your cart.';


    /**
     *
     */
    public function __invoke(Request $request, Cart $cart, Products $products, Checkout $checkout, $product, Registration $registration)
    {
        try {
            $product  = $products->findOneBySlug($product) ?? $products->find($product) ?? null;

            if (!$product) {
                throw new Exceptions\Cart\NotFoundException(static::MSG_NOT_FOUND);
            }

            $checkout->reset($cart);

            $request_form = 'regular_form';
            if ($request->get('form') === 'mental_health') {
                $request_form = 'mental_health';
            } else if ($request->get('form') === 'weight') {
                $request_form = 'weight';
            }

            if ($product->getCategory()->getSlug() === 'weight-lost') {
                $productItems = self::addWeightLostProductsIntoCardForDefaultPrescription($products, $product, $request_form);

                if (count($productItems) > 0) {
                    foreach ($productItems as $productItem) {
                        $cart->add($productItem);
                    }
                }
            } else {
                $productItem = [
                    "id" => (string)$product->getId(),
                    "name" => $product->getName(),
                    "qty" => 1,
                    "price" => $product->getPrice(),
                    "weight" => 0,
                    "options" => [
                        "dose" => $product->getStrength(),
                        "pills" => $product->getQuantity(),
                        "request_form" => $request_form
                    ]
                ];
                $cart->add($productItem);
            }

            $this->session->flash('success', sprintf(static::MSG_SUCCESS, $product));
        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        return $this->redirect('cart');
    }

    private function addWeightLostProductsIntoCardForDefaultPrescription(Products $products, $selectedProduct, $request_form)
    {
        if ($selectedProduct->getCategory()->getSlug() === 'weight-lost') {
            $semaglutideDefaultPrescriptionSet = [
                [
                    'strength' => '0.25mg',
                    'quantity' => '2.5mg',
                    'order_qnt' => 1,
                ],[
                    'strength' => '0.5mg',
                    'quantity' => '2.5mg',
                    'order_qnt' => 1,
                ],[
                    'strength' => '1mg',
                    'quantity' => '5mg',
                    'order_qnt' => 1,
                ],[
                    'strength' => '1.7mg',
                    'quantity' => '10mg',
                    'order_qnt' => 1,
                ],[
                    'strength' => '2.4mg',
                    'quantity' => '10mg',
                    'order_qnt' => 8,
                ]
            ];

            $tirzepatideDefaultPrescriptionSet = [
                [
                    'strength' => '2.5mg',
                    'quantity' => '10mg',
                    'order_qnt' => 1,
                ], [
                    'strength' => '5mg',
                    'quantity' => '20mg',
                    'order_qnt' => 1,
                ], [
                    'strength' => '7.5mg',
                    'quantity' => '30mg',
                    'order_qnt' => 1,
                ], [
                    'strength' => '10mg',
                    'quantity' => '40mg',
                    'order_qnt' => 1,
                ], [
                    'strength' => '12.5mg',
                    'quantity' => '50mg',
                    'order_qnt' => 1,
                ], [
                    'strength' => '15mg',
                    'quantity' => '20mg',
                    'order_qnt' => 7,
                ]
            ];

            if ($selectedProduct->getName() === 'Semaglutide') {
                $consultationDefaultPrescriptionSet = $semaglutideDefaultPrescriptionSet;
            }
            if ($selectedProduct->getName() === 'Tirzepatide') {
                $consultationDefaultPrescriptionSet = $tirzepatideDefaultPrescriptionSet;
            }

            $items = [];
            foreach ($consultationDefaultPrescriptionSet as $consultationDefaultPrescriptionItem) {
                $product = $products->findOneBy([
                    'name' => $selectedProduct->getName(),
                    'strength' => $consultationDefaultPrescriptionItem['strength'],
                    'quantity' => $consultationDefaultPrescriptionItem['quantity']
                ]);
                if ($product) {
                    $items[] = [
                        "id" => (string)$product->getId(),
                        "name" => $product->getName(),
                        "qty" => (int)$consultationDefaultPrescriptionItem['order_qnt'],
                        "price" => $product->getPrice(),
                        "weight" => 0,
                        "options" => [
                            "dose" => $product->getStrength(),
                            "pills" => $product->getQuantity(),
                            "request_form" => $request_form
                        ]
                    ];
                }
            }
            return $items;
        }
    }
}
