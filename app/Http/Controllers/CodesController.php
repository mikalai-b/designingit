<?php

namespace App\Http\Controllers;

use Campaigns;
use CouponCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CodesController extends AbstractController
{
    const IMPORT_MESSAGE_SUCCESS = '%s code%s been imported. Skipped %s code%s.';

    public function index($campaignId, Campaigns $campaignsRepository, CouponCodes $codesRepository)
    {
        $campaign = $campaignsRepository->find($campaignId);

        $currentCodeCount = $codesRepository->count(
            $codesRepository->getQueryBuilder()
                ->where('this.campaign = ?1')
                ->setParameter(1, $campaign)
            );

        return $this->render('pages.codes.index', 200, ['campaign' => $campaign, 'currentCodeCount' => $currentCodeCount]);
    }

    public function store($campaignId, Request $request, Campaigns $campaignsRepository, CouponCodes $codesRepository)
    {
        $campaign = $campaignsRepository->find($campaignId);
        $newCodes = preg_split("/\n/", $request->input('codes'));
        $skippedCodes = [];
        $importedCodes = [];
        foreach ($newCodes as $newCode) {
            $newCode = trim($newCode);
            try {
                $couponCode = $codesRepository->create();
                $couponCode->setCode($newCode);
                $couponCode->setCampaign($campaign);
                $couponCode->setUnlimited($request->input('unlimited', 0));
                $couponCode->setRedemptionCount(0);
                $codesRepository->store($couponCode, true);
                $importedCodes[] = $newCode;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $skippedCodes[] = $newCode;
            }
        }
        if (count($skippedCodes)) {
            $skipMessage = sprintf('Skipped code(s): %s', join(', ', $skippedCodes));
            $this->session->flash('error', $skipMessage);
            Log::info($skipMessage);
        }
        $message = sprintf(static::IMPORT_MESSAGE_SUCCESS, count($importedCodes), (count($importedCodes) == 1 ? ' has' : 's have'), count($skippedCodes), (count($skippedCodes) == 1 ? null : 's'));
        $this->session->flash('success', $message);
        return $this->redirect('campaigns.index');
    }
}
