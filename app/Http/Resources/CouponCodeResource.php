<?php

use Illuminate\Http\Resources\Json\Resource;

class CouponCodeResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => $this->getCode(),
            'redeemed' => $this->getRedeemed(),
        ];
    }
}
