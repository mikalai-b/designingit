<?php

use Illuminate\Http\Resources\Json\Resource;

class MessageResource extends Resource
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
            'id'          => $this->getId(),
            'parent'      => $this->getParent() ? $this->getParent()->getId() : NULL,
            'body'        => $this->getBody(),
            'dateCreated' => $this->getDateCreated(),
            'sender'      => new PersonResource($this->getSender()),
            'recipients'  => $this->getReceipts()->map(function($receipt) {
                return $receipt->getRecipient()->getId();
            })->toArray()
        ];
    }
}
