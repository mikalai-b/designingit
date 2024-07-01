<?php

namespace App\Http\Actions;

use Consultations;
use Messages;
use People;
use MessageFormInspector;
use App\Exceptions;

/**
 *
 */
class CreateMessage extends AbstractAction
{
    const MSG_SUCCESS = 'Your message has been successfully sent.';

    /**
     *
     */
    public function __invoke(Messages $messages, People $people, Consultations $consultations, MessageFormInspector $inspector)
    {
        $parent_msg_id   = $this->request->input('pid', NULL);
        $body            = $this->request->input('body', NULL);
        $user            = $this->auth->user();
        $contacts        = $people->findForSender($user);

        if ($parent_msg_id) {
            if (!$parent_msg = $messages->find($parent_msg_id)) {
                throw new MissingEntityException(static::MSG_MESSAGE_NOT_FOUND);
            }

            $message   = $messages->createFromParent($parent_msg);
            $recipient = $parent_msg->getSender();

        } else {
            $message   = $messages->create();
            $recipient = $people->find($this->request->input('recipient', 0));

            $message->setSubject($this->request->input('subject', NULL));
        }

        try {

            if ($this->request->getMethod() == 'POST') {
                $message->setSender($user);

                if (strip_tags(trim($body))) {
                    $message->setBody($body);
                }

                $inspector->run($this->request->input());

                $messages->send($message, $recipient);
                $this->session->flash('success', static::MSG_SUCCESS);
                return $this->redirect('dashboard');
            }

        } catch(Exceptions\ValidationException $e) {
            $errors = $e->getMessages();

            $this->session->flash('error', $e->getMessage());

            return $this->render('pages/dashboard/compose', 400, get_defined_vars());
        }

        return $this->render('pages/dashboard/compose', 200, get_defined_vars());
    }
}
