<?php

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
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
            'fullName' => (string) $this->resource,
            'name' => $this->getName(),
            'prescriptionOnly' => $this->getPrescriptionOnly(),
            'strength' => $this->getStrength(),
            'quantity' => $this->getQuantity(),
            'info' => $this->getInfo(),
            'price' => $this->getPrice(),
            'grouponPrice' => $this->getGrouponPrice(),
            'thumbnail' => $this->getThumbnail(),
            'type' => new ProductTypeResource($this->getType()),
            'category' => new ProductCategoryResource($this->getCategory()),
            'id' => $this->getId(),
        ];
    }
}
