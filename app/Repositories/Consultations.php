<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Services\Cart;

/**
 *
 */
class Consultations extends Repository
{
    /**
     *
     */
    static protected $entity = 'Consultation';


    /**
     *
     */
    static protected $order = ['dateCreated' => 'DESC'];


    /**
     *
     */
    public function __construct(EntityManager $em, ConsultationInspector $inspector, Products $products, Questions $questions, Symptoms $symptoms, Answers $answers, Photos $photos)
    {
        $this->inspector = $inspector;
        $this->products  = $products;
        $this->questions = $questions;
        $this->symptoms  = $symptoms;
        $this->answers   = $answers;
        $this->photos    = $photos;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function create()
    {
        $consultation = parent::create();

        $consultation->setStatus(constant(static::$entity . '::STATUS_NEW'));

        return $consultation;

    }


    /**
     *
     */
    public function createFromCart($cart)
    {
        $consultation = $this->create();
        $product = $this->products->findForCart($cart)[0] ?? null;

        foreach ($this->questions->findForCart($cart) as $question) {
            $answer = $this->answers->create();

            $answer->setConsultation($consultation);
            $answer->setQuestion($question->getFilledContent($product));
            $answer->setQuestionConfig($question->getConfig());
            $answer->setDisplayOrder($question->getDisplayOrder());
            $answer->setType($question->getType());

            $consultation->getAnswers()->add($answer);
        }

        return $consultation;
    }

    public function createFromCartMentalHealth(Cart $cart, $consultationFormData)
    {
        $consultation = $this->create();
        foreach ($consultationFormData as $key => $questionItem) {
            $userAnswer = $questionItem->answer;
            if (is_array($questionItem->answer)) {
                $userAnswer = implode(';', $questionItem->answer);
            }
            $answer = $this->answers->create();
            $answer->setConsultation($consultation);
            $answer->setQuestion($questionItem->question);
            $answer->setContent($userAnswer);
            $answer->setQuestionConfig(str_replace('"', '', $questionItem->type));
            $answer->setDisplayOrder(1);
            $consultation->getAnswers()->add($answer);
        }

        return $consultation;
    }

    public function createFromCartWeight(Cart $cart, $consultationFormData)
    {
        $consultation = $this->create();
        foreach ($consultationFormData as $key => $questionItem) {
            $userAnswer = $questionItem->answer;
            if (is_array($questionItem->answer)) {
                $userAnswer = implode(';', $questionItem->answer);
            }
            $answer = $this->answers->create();
            $answer->setConsultation($consultation);
            $answer->setQuestion($questionItem->question);
            $answer->setContent($userAnswer);
            $answer->setQuestionConfig(str_replace('"', '', $questionItem->type));
            $answer->setDisplayOrder(1);
            $consultation->getAnswers()->add($answer);
        }

        return $consultation;
    }


    /**
     *
     */
    public function findForPatient(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.order', 'o')
                ->where('o.person = ?1')
                ->setParameter(1, $person)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findOpenForProvider(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.order', 'o')
                ->where('o.provider = ?1')
                ->andWhere('this.status = ?2')
                ->setParameter(1, $person)
                ->setParameter(2, constant(static::$entity . '::STATUS_OPEN'))
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findUnclosedForProvider(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.order', 'o')
                ->where('o.provider = ?1')
                ->andWhere('this.status != ?2')
                ->setParameter(1, $person)
                ->setParameter(2, constant(static::$entity . '::STATUS_COMPLETED'))
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findSubmittedAndUnclosedForProvider(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.order', 'o')
                ->where('o.provider = ?1')
                ->andWhere($query->expr()->notIn('this.status', [constant(static::$entity . '::STATUS_COMPLETED'), constant(static::$entity . '::STATUS_NEW')]))
                ->setParameter(1, $person)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function getSymptoms()
    {
        return $this->symptoms;
    }



    /**
     *
     */
    public function inspect(Consultation $consultation)
    {
        return $this->inspector->run($consultation);
    }
}
