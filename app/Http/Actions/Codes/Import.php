<?php

namespace App\Http\Actions\Codes;

use App\Http\Actions\AbstractAction;
use CouponCodes;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Products;

class Import extends AbstractAction
{
    const MSG_SUCCESS = '%s code%s been imported. Skipped %s code%s.';

    public function __invoke(Request $request, CouponCodes $couponCodes, Products $productsRepository, EntityManager $em)
    {
        $currentCodeCount = $couponCodes->count($couponCodes->getQueryBuilder());

        if ($this->request->getMethod() == 'POST') {
            $newCodes = preg_split("/\n/", $request->input('codes'));
            $skippedCodes = [];
            $importedCodes = [];
            $products = $request->input('products') ? $productsRepository->findById($request->input('products')) : null;
            foreach ($newCodes as $newCode) {
                $newCode = trim($newCode);
                if ($newCode) {
                    try {
                        $couponCode = $couponCodes->create();
                        $couponCode->setCode($newCode);
                        $couponCode->setCampaign($request->input('campaign'));
                        $couponCode->setProducts($products);
                        $couponCode->setUnlimited($request->input('unlimited', 0));
                        $couponCodes->store($couponCode, TRUE);
                        $importedCodes[] = $newCode;
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        $skippedCodes[] = $newCode;
                    }
                }
            }
            if (count($skippedCodes)) {
                $skipMessage = sprintf('Skipped code(s): %s', join(', ', $skippedCodes));
                $this->session->flash('error', $skipMessage);
                Log::info($skipMessage);
            }
            $message = sprintf(static::MSG_SUCCESS, count($importedCodes), (count($importedCodes) == 1 ? ' has' : 's have'), count($skippedCodes), (count($skippedCodes) == 1 ? null : 's'));
            $this->session->flash('success', $message);
            return $this->redirect('codes');
        }
        $products = $productsRepository->findAll(['name'=>'asc']);
        return $this->render('pages.codes.import', 200, ['codeCount'=>$currentCodeCount , 'products'=>$products]);
    }
}