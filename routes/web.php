<?php

use App\Services\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//
// Public Actions
//




//
// Guest Actions
//

Route::middleware(['guest'])->group(function() {
    Route::any('/login', 'Actions\Login@__invoke')->name('login');
    Route::any('/password/forgot', 'Actions\ForgotPassword@__invoke')->name('password.request');
    Route::any('/password/reset/{token?}', 'Actions\ResetPassword@__invoke')->name('password.reset');
});

//
//
//

Route::middleware(['auth'])->group(function() {
    Route::any('/auth', 'Actions\TwoFactorAuth@__invoke')->name('2fa');
});


//
//
//

Route::middleware(['auth', 'auth.2fa'])->group(function() {
    Route::get('/users', 'Actions\Users\Manage@__invoke')->name('users');
    Route::post('/users', 'Actions\Users\Create@__invoke');
    Route::get('/users/{id}', 'Actions\Users\Select@__invoke')->name('user');
    Route::put('/users/{id}', 'Actions\Users\Update@__invoke');
    Route::delete('/users/{id}', 'Actions\Users\Remove@__invoke');
});

Route::get('/coupon/{code}', 'Actions\RedeemCouponCodeAction');


//
// Cart
//

Route::prefix('/cart')->middleware('cart.status')->group(function() {
    Route::get('/', 'Actions\Cart\View@__invoke')->name('cart');
    Route::any('/items/{item}', 'Actions\Cart\Update@__invoke')->name('cart.update');
    Route::any('/products/{product}', 'Actions\Cart\Add@__invoke')->name('cart.add');
    Route::any('/resume/{order}', 'Actions\Cart\Resume@__invoke')->name('cart.resume');

    Route::middleware(['guest'])->group(function() {
        Route::any('/register', 'Actions\Cart\Register@__invoke')->name('cart.register');
    });

    Route::middleware(['auth', 'auth.2fa'])->group(function() {
        Route::any('/order', 'Actions\Cart\AddOrder@__invoke')->name('cart.order');
        Route::any('/billing', 'Actions\Cart\AddBilling@__invoke')->name('cart.billing');
        Route::any('/consultation', 'Actions\Cart\AddConsultation@__invoke')->name('cart.consultation');
        Route::any('/photos', 'Actions\Cart\AddPhotos@__invoke')->name('cart.photos');
        Route::get('/thanks', 'Actions\Cart\Thanks@__invoke')->name('cart.thanks');
    });
});

Route::get('/cart/disabled', 'Actions\Cart\Disabled@__invoke')->name('cart.disabled');

//
// Dashboard
//

Route::prefix('/dashboard')->middleware(['auth', 'auth.2fa'])->group(function() {
    Route::any('/insert-two-products', 'Actions\ViewDashboard@insertTwoMentalProducts');
    Route::any('/insert-new-mental-health-products', 'Actions\ViewDashboard@insertNewMentalProducts');
    Route::any('/update-semaglutide', 'Actions\ViewDashboard@updateSemaglutide');
    Route::any('/update-tirzepatide', 'Actions\ViewDashboard@updateTirzepatide');

    Route::any('/', 'Actions\ViewDashboard@__invoke')->name('dashboard');
    Route::any('/info', 'Actions\EditInfo@__invoke');
    Route::any('/address', 'Actions\EditAddress@__invoke');
    Route::any('/password', 'Actions\EditPassword@__invoke');
    Route::any('/billing', 'Actions\EditBilling@__invoke');

    Route::any('/logout', 'Actions\Logout@__invoke')->name('logout');

    Route::any('/compose', 'Actions\CreateMessage@__invoke')->name('compose');
    Route::get('/messages/{id}', 'Actions\ViewMessage@__invoke')->name('message');

    Route::get('/consultations', 'Actions\Consultations\Manage@__invoke')->name('consultations');

    Route::post('/reorder/{id}', 'Actions\StartReorder@__invoke');

    //
    // TODO: The routes below should be converted similar to patients
    // Consultations\Select
    // Consultations\Update
    //

    Route::get('/consultations/{id}', 'Actions\ViewConsultation@__invoke')->name('consultation');
    Route::post('/consultations/{id}', 'Actions\EditConsultation@__invoke');
    Route::post('/consultations/{id}/photos', 'Actions\EditPhotos@__invoke')->name('edit-photos');

    Route::get('/patients', 'Actions\Patients\Manage@__invoke')->name('patients');
    Route::get('/patients/{id}', 'Actions\Patients\Select@__invoke')->name('patient');
    Route::put('/patients/{id}', 'Actions\Patients\Update@__invoke');

    Route::get('/prescriptions/{id}', 'Actions\ViewPrescription@__invoke')->name('prescription');
    Route::post('/prescriptions/{id}', 'Actions\EditPrescription@__invoke');
    Route::post('/update-single-prescriptions/{id}', 'Actions\ViewPrescription@updateSinglePrescription')->name('update.single.prescription');
    Route::post('/update-prescription-set-status', 'Actions\ViewPrescription@updatePrescriptionSetStatus')->name('update.prescriptionSet.status');

    Route::any('/codes', 'Actions\Codes\Import@__invoke')->name('codes');

});

//
// API
//

Route::prefix('/api/v1')->group(function() {

    Route::get('/products', function(Products $products) {
        return ProductResource::collection($products->findBy([], ['name' => 'ASC']));
    });
    Route::get('/products/{id}', function(Products $products, $id) {
        return new ProductResource($products->find($id));
    });

    Route::get('/cart', function(Cart $cart) {
        return new CartResource($cart);
    });

    Route::get('/providers/', 'Actions\API\Providers@get');

    Route::get('/users/{id}', function(People $people, $id) {
        if ($id == 'current') {
            $person = Auth::user();
        } else {
            $person = $people->find($id);
        }

        if (Gate::allows('select', $person)) {
            return new PersonResource($person);
        }

        return response()->json(false);
    });

    Route::post('coupon-code/validate', 'Actions\API\CouponCodes\ApplyCouponCodeToCartAction@__invoke');
    Route::post('coupon-code/remove', 'Actions\API\CouponCodes\RemoveCouponCodeFromCartAction@__invoke');


    Route::middleware(['auth', 'auth.2fa'])->group(function() {

        Route::group(['middleware'=>'can:manage,\Campaign'], function() {
            Route::get('/campaigns/{campaign_id}/coupon-codes', 'Actions\API\CouponCodes\ListCouponCodesAction');
            Route::get('/offers', 'Actions\API\Offers\GetOffersAction');
            Route::post('/offers', 'Actions\API\Offers\SaveOfferAction');
            Route::delete('/offers/{id}', 'Actions\API\Offers\RemoveOfferAction');
        });

        Route::get('/messages/', 'Actions\API\Messages@__invoke');
        Route::post('/messages/', 'Actions\API\Messages@post');

        Route::get('/messages/new/', 'Actions\API\Messages@getNew');
        Route::get('/messages/latest/', 'Actions\API\Messages@getLatest');
        Route::get('/messages/since/{id}', 'Actions\API\Messages@getSince');
        Route::get('/messages/before/{id}', 'Actions\API\Messages@getBefore');

        Route::post('orders/{id}/apply-coupon-code', 'Actions\API\CouponCodes\ApplyCouponCodeToOrderAction@__invoke');
        Route::post('orders/{id}/remove-coupon-code', 'Actions\API\CouponCodes\RemoveCouponCodeFromOrderAction@__invoke');

        Route::post('prescriptions/{id}/refill-frequency', 'Actions\API\Prescriptions@updateRefillFrequency');
        Route::post('prescriptions/{id}/pause', 'Actions\API\Prescriptions@pause');
        Route::post('prescriptions/{id}/resume', 'Actions\API\Prescriptions@resume');
        Route::post('prescriptions/{id}/cancel', 'Actions\API\Prescriptions@cancel');

        Route::get('/consultation/{consultation_id}/photos/{type_id}', function(Consultations $consultations, $consultationId, $typeId) {
            if (!$consultation = $consultations->find($consultationId)) {
                return $this->respond(NULL, 404);
            }

            $array = [];

            foreach ($consultation->getPhotoTypes() as $photoType) {
                if ($photoType->getId() == $typeId) {
                    $photos = $consultation->getPhotosForType($photoType);
                    foreach ($photos as $photo) {
                        $array[] = ['id'=>$photo->getId(), 'file'=>$photo->getS3Url()];
                    }
                    return response()->json($array);
                }
            }
        });
    });
});

Route::group(['prefix' => '/dashboard', 'middleware'=>['auth', 'auth.2fa']], function (){
    Route::group(['middleware'=>'can:manage,\Campaign'], function() {

        Route::get('campaigns', 'Controllers\CampaignsController@index')->name('campaigns.index');
        Route::post('campaigns', 'Controllers\CampaignsController@store')->name('campaigns.store');
        Route::get('campaigns/create', 'Controllers\CampaignsController@create')->name('campaigns.create');
        Route::get('campaigns/{campaign_id}', 'Controllers\CampaignsController@edit')->name('campaigns.edit');
        Route::post('campaigns/{campaign_id}', 'Controllers\CampaignsController@update')->name('campaigns.update');

        Route::get('campaigns/{campaign_id}/codes', 'Controllers\CodesController@index')->name('codes.index');
        Route::post('campaigns/{campaign_id}/codes', 'Controllers\CodesController@store')->name('codes.store');

        Route::get('campaigns/{campaign_id}/offers', 'Controllers\OffersController@index')->name('offers.index');
    });
});

