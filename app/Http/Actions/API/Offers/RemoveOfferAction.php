<?php

namespace App\Http\Actions\API\Offers;

use App\Http\Actions\AbstractAction;
use Offers;

class RemoveOfferAction extends AbstractAction
{
    const SUCCESS_MESSAGE = 'Product has been removed from campaign.';

    public function __invoke($offerId, Offers $offers)
    {
        $offer = $offers->find($offerId);
        $offers->remove($offer);

        return response()->json([
            'message' => static::SUCCESS_MESSAGE
        ]);
    }
}
