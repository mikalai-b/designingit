<?php

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 *
 */
class MessageCollection extends ResourceCollection
{
    public $collects = 'MessageResource';

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
