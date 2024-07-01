<?php
namespace App\Http\Actions\Cart;

use Checkout;
use Registration;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 *
 */
class Register extends AbstractAction
{
    use AuthenticatesUsers;

    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout, Registration $registration)
    {
        //$active_step = 'cart';
        $email    = $this->request->input('email', NULL);
        $password = $this->request->input('password', NULL);
        $agree    = $this->request->input('agree', NULL);

        try {
            $valid = $this->guard()->attempt(
                [
                    $this->username() => $email,
                    'password' => $password
                ],
                $this->request->filled('remember')
            );

            if (!$valid) {
                $person = $registration->create($email, $password, $agree);
                $this->auth->login($person);
            }

            return $this->redirect('cart');

        } catch (ValidationException $e) {
            $errors = $registration->getErrors();

            $this->session->flash('error', $e->getMessage());
        }

        $alt_products = $checkout->getProducts()->findSuggestions($cart);
        return $this->render('pages.cart.index', 200, get_defined_vars());
    }
}
