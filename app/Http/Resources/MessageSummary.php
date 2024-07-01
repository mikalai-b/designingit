<?php

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 *
 */
class MessageSummary extends ResourceCollection
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
        $data = array();

        foreach ($this->collection as $message) {
            foreach ($data as $summary) {
                if ($summary['sender'] === new PersonResource($message->getSender())) {
                    $summary['messages'][] = $message;

                    break;
                }
            }

            $data[] = [
                'sender'   => new PersonResource($message->getSender()),
                'messages' => [$message]
            ];
        }

        return $data;
    }
}
