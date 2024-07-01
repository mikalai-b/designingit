<?php

namespace App\Http\Actions\API;

use Messages as Repository;
use MessageResource;
use MessageCollection;
use MessageSummary;

use App\Http\Actions\AbstractAction;
use App\Exceptions\ValidationException;

/**
 *
 */
class Messages extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Repository $messages)
    {
        extract($this->request->all() + [
            'with'  => NULL
        ]);

        $person = $this->auth->user();

        if ($with) {
            $with = $messages->getPeople()->find($with);
        }

        $results = $messages->findNew($person, $with);

        return new MessageSummary($results);
    }


    /**
     *
     */
    public function post(Repository $messages)
    {
        extract($this->request->all() + [
            'to'   => [],
            're'   => NULL,
            'body' => NULL
        ]);

        $person  = $this->auth->user();
        $message = $messages->create($person, $body);

        if ($to) {
            $recipients = $messages->getPeople()->findById($to);

        } elseif ($re) {
            $parent_msg = $messages->find($re);
            $recipients = $messages->getPeople()->findForReply($parent_msg, $person);

            $message->setParent($parent_msg);

        } else {
            $recipients = $messages->getPeople()->findDefaultRecipients($person);
        }

        $messages->send($message, ...$recipients);

        return new MessageResource($message);
    }


    /**
     *
     */
    public function getBefore(Repository $messages, $before = NULL)
    {
        extract($this->request->all() + [
            'with'  => NULL,
            'limit' => NULL,
            'mark'  => TRUE
        ]);

        $person = $this->auth->user();

        if ($with) {
            $with = $messages->getPeople()->find($with);
        }

        $results = $messages->findBefore($before, $person, $with, $limit);

        if ($mark) {
            foreach ($results as $result) {
                $result->markSeenBy($person);
            }
        }

        return new MessageCollection($results);
    }


    /**
     *
     */
    public function getLatest(Repository $messages)
    {
        extract($this->request->all() + [
            'with'  => NULL,
            'limit' => 5,
            'mark'  => TRUE
        ]);

        $person = $this->auth->user();

        if ($with) {
            $with = $messages->getPeople()->find($with);
        }

        $results = $messages->findLatest($person, $with, $limit);

        if ($mark) {
            foreach ($results as $result) {
                $result->markSeenBy($person);
            }
        }

        return new MessageCollection($results);
    }


    /**
     *  
     */
    public function getNew(Repository $messages)
    {
        extract($this->request->all() + [
            'with'  => NULL
        ]);

        $person = $this->auth->user();

        if ($with) {
            $with = $messages->getPeople()->find($with);
        }

        $results = $messages->findNew($person, $with);

        return new MessageCollection($results);
    }


    /**
     *
     */
    public function getSince(Repository $messages, $since = NULL)
    {
        extract($this->request->all() + [
            'with'  => NULL,
            'limit' => NULL,
            'mark'  => TRUE
        ]);

        $person = $this->auth->user();

        if ($with) {
            $with = $messages->getPeople()->find($with);
        }

        $results = $messages->findSince($since, $person, $with, $limit);

        if ($mark) {
            foreach ($results as $result) {
                $result->markSeenBy($person);
            }
        }

        return new MessageCollection($results);
    }
}
