<?php

use Illuminate\Http\Resources\Json\Resource;

class PersonResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $s3 = App::make('Extensions\Twig\S3');

        return [
            'id'        => $this->getId(),
            'label'     => $this->__toString(),
            'firstName' => $this->getFirstName(),
            'lastName'  => $this->getLastName(),
            'avatar'    => $this->getAvatar()
                ? $s3->url($this->getAvatar())
                : NULL
        ];
    }
}
