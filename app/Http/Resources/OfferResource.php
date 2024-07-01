<?php

use Illuminate\Http\Resources\Json\Resource;

class OfferResource extends Resource
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
            'id' => $this->getId(),
            'product' => new ProductResource($this->getProduct()),
            'price' => $this->getPrice(),
            'firstShipmentPrice' => $this->getFirstShipmentPrice(),
            'successMessage' => $this->getSuccessMessage(),
        ];
    }
}
