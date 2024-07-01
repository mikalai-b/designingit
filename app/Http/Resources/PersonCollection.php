<?php

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 *
 */
class PersonCollection extends ResourceCollection
{
    public $collects = 'PersonResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
