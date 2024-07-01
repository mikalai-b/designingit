<?php

namespace App\Http\Actions\API\Offers;

use App\Http\Actions\AbstractAction;
use Illuminate\Http\Request;
use OfferResource;
use Offers;

class GetOffersAction extends AbstractAction
{
    public function __invoke(Request $request, Offers $offers)
    {
        return OfferResource::collection($offers->findBy([
            'campaign' => $request->input('campaignId')
        ]));
    }
}