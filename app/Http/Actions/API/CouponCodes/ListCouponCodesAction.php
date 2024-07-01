<?php
namespace App\Http\Actions\API\CouponCodes;

use App\Http\Actions\AbstractAction;
use Campaigns;
use CouponCodeResource;
use CouponCodes;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

class ListCouponCodesAction extends AbstractAction
{
    public function __invoke($campaignId, Campaigns $campaignRepository, CouponCodes $couponCodeRepository, Request $request, EntityManager $entityManager)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 100);
        $campaign = $campaignRepository->find($campaignId);

        $codes = $couponCodeRepository->findBy([
            'campaign' => $campaign,
        ], ['code' => 'asc'], $perPage, ($page - 1) * $perPage);
        
        return response()->json(CouponCodeResource::collection($codes));
    }
}