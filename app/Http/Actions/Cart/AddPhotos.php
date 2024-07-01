<?php
namespace App\Http\Actions\Cart;

use Checkout;
use Photos;
use Products;
use Consultations;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use Illuminate\Support\Facades\Cookie;

/**
 *
 */
class AddPhotos extends AbstractAction
{

    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout, Photos $photos, Consultations $consultations)
    {
        $active_step  = 'cart.photos';
        $person       = $this->auth->user();
        $types        = $photos->getTypes();
        $order        = $checkout->getCurrentOrder();
        $consultation = $order->getConsultation();

        if (count($cart->content()) > 0) {
            $firstItem = $cart->content()->first()->toArray();
            $firstItemCategory = $firstItem['options']['request_form'];
        }

        if (!count($cart->content())) {
            return $this->redirect('cart');
        }

        if ($checkout->getCurrentStep() != $active_step && !$checkout->hasCompletedStep($active_step)) {
            return $this->redirect($checkout->getCurrentStep());
        }

        if ($this->request->getMethod() == 'POST') {

            //
            // Remove any photos requested for removal
            //

            $consultation->removePhotos($photos->findById($this->request->input('remove')));

            //
            // Add any new photos
            //

            foreach ($this->request->file('photos', array()) as $type_id => $files) {
                $type = $photos->getTypes()->find($type_id);

                foreach ($files as $file) {
                    $photo = $photos->create();

                    $photo->setType($type);
                    $photo->setFile($file);
                    $photo->setConsultation($consultation);
                    $photos->store($photo, TRUE);

                    $consultation->getPhotos()->add($photo);
                }
            }

            if ($checkout->getCouponCode() && !$checkout->couponCodeIsValid()) {
                $this->session->flash('error', 'The coupon code you entered is no longer valid.');
                $order->setCouponCode(null);
                $checkout->loadNormalPricing();
                return $this->redirect('cart.billing');
            }

            $order->setPending();
            $consultation->setOpen();
            Cookie::queue(Cookie::forget('couponCode'));

            return $this->redirect('cart.thanks', 303);
        }

        return $this->render('pages.cart.photos', 200, get_defined_vars());
    }
}
