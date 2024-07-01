<?php

namespace App\Http\Actions\API\Offers;

use App\Http\Actions\AbstractAction;
use Campaigns;
use Illuminate\Http\Request;
use Offer;
use OfferResource;
use Offers;
use Products;

class SaveOfferAction extends AbstractAction
{
    public function __invoke(Request $request, Offers $offers, Campaigns $campaigns, Products $products)
    {
        // See if offer for product and campaign exists first...
        $offer = $offers->findOneBy([
            'campaign' => $request->input('campaignId'),
            'product' => $request->input('productId'),
        ]);
        if (!$offer) {
            $offer = new Offer();
            $offer->setCampaign($campaigns->find($request->input('campaignId')));
            $offer->setProduct($products->find($request->input('productId')));
        }
        $offer->setPrice($request->input('price'));
        $offer->setSuccessMessage($request->input('successMessage'));
        $offer->setFirstShipmentPrice($request->input('firstShipmentPrice'));
        $offer->setSecondShipmentPrice($request->input('secondShipmentPrice'));
        $offers->store($offer, true);

        return new OfferResource($offer);
    }
}