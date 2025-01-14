<?php

use Illuminate\Http\Resources\Json\Resource;

class ProductCategoryResource extends Resource
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
            'id'    => $this->getId(),
            'title' => $this->getTitle()
        ];
    }
}
