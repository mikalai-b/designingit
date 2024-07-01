<?php

namespace App\Http\Controllers;

use Campaigns;
use Offers;

class OffersController extends AbstractController
{

    public function index($campaignId, Campaigns $campaignsRepository, Offers $offersRepository)
    {
        $campaign = $campaignsRepository->find($campaignId);
        
        $offers = $offersRepository->findBy([
            'campaign' => $campaign
        ]);
        
    }

}