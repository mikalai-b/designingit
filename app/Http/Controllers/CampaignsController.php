<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use Campaign;
use Campaigns;
use DateTime;

class CampaignsController extends AbstractController
{

    public function index(Campaigns $campaignsRepository)
    {
        $campaigns = $campaignsRepository->findAll(['title'=>'asc']);
        return $this->render('pages.campaigns.index', 200, ['campaigns'=>$campaigns]);
    }

    public function create()
    {
        return $this->render('pages.campaigns.create', 200, ['campaign' => new Campaign]);
    }

    public function store(CampaignRequest $request, Campaigns $campaignsRepository)
    {
        $campaign = new Campaign();
        $this->populate($campaign, $request->validated());
        $campaignsRepository->store($campaign, true);
        $this->session->flash('success', 'Campaign has been saved');
        return $this->redirect('campaigns.edit', 303, ['campaign_id' => $campaign->getId()]);
    }

    public function edit($campaignId, Campaigns $campaignsRepository)
    {
        $campaign = $campaignsRepository->find($campaignId);
        return $this->render('pages.campaigns.edit', 200, ['campaign' => $campaign]);
    }

    public function update($campaignId, CampaignRequest $request, Campaigns $campaignsRepository)
    {
        $campaign = $campaignsRepository->find($campaignId);
        $this->populate($campaign, $request->validated());
        $campaignsRepository->store($campaign, true);
        $this->session->flash('success', 'Campaign has been saved');
        return $this->redirect('campaigns.index');
    }

    public function populate(Campaign $campaign, array $data)
    {
        $campaign->setTitle($data['title'] ?? null);
        $campaign->setStartDate($data['start_date'] ? new DateTime($data['start_date']) : null);
        $campaign->setEndDate($data['end_date'] ? new DateTime($data['end_date']) : null);
        $campaign->setSuccessMessage($data['success_message'] ?? null);
        $campaign->setEffects($data['effects'] ?? null);
    }


}