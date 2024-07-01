<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use App\Notifications\NewChatMessage;

/**
 *
 */
class Messages extends Repository
{
    /**
     * @var string
     */
    const MSG_MISSING_CONSULTATION = 'Sorry, we are unable to find that consultation on file.';

    /**
     *
     */
    static protected $entity = 'Message';


    /**
     *
     */
    static protected $order = [];


    /**
     *
     */
    protected $twig = NULL;


    /**
     *
     */
    public function __construct(EntityManager $em, People $people, MessageReceipts $receipts, TwigBridge\Bridge $twig)
    {
        $this->people   = $people;
        $this->receipts = $receipts;
        $this->twig     = $twig;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function create(Person $sender = NULL, $body = NULL)
    {
        $message = parent::create();

        $message->setSender($sender);
        $message->setBody($body);

        return $message;
    }


    /**
     *
     */
    public function createReply(Message $parent_message, Person $sender = NULL, $body = NULL)
    {
        $message = $this->create($sender, $body);

        $message->setParent($parent_message);

        return $message;
    }


    /**
     *
     */
    public function findBefore($before, Person $person, Person $with = NULL, $limit = 5)
    {
        return $this->collect($this->query(function($builder) use ($before, $person, $with, $limit) {
            $before_message = $this->find($before);

            if ($with) {
                $this->queryBetweenPeople($builder, [$person, $with]);
            } else {
                $this->queryForPerson($builder, $person);
            }

            if ($limit) {
                $this->paginate($builder, $limit, 1);
            }

            $builder->andWhere('this.id != :before_message_id');
            $builder->andWhere('this.dateCreated <= :before_date_created');
            $builder->setParameter('before_message_id', $before_message->getId());
            $builder->setParameter('before_date_created', $before_message->getDateCreated());
        }));
    }


    /**
     *
     */
    public function findBetweenPeople(Person ...$people)
    {
        return $this->collect($this->query(function($builder) use ($people) {
            $this->queryBetweenPeople($builder, $people);
        }));
    }


    /**
     *
     */
    public function findForPerson(Person $person, $include_seen = TRUE, $include_reviewed = TRUE)
    {
        return $this->collect($this->query(function($builder) use ($person) {
            $this->queryForPerson($builder, $person);
        }));
    }


    /**
     *  Find all new messages
     */
    public function findNew(Person $person, Person $with = NULL)
    {
        return $this->collect($this->query(function($builder) use ($person, $with) {
            $this->queryToPerson($builder, $person, $with);

            $builder->andWhere('r.dateSeen is null');
        }));
    }


    /**
     * Find the latest messages for a person
     *
     */
    public function findLatest(Person $person, Person $with = NULL, $limit = 5)
    {
        return $this->collect($this->query(function($builder) use ($person, $with, $limit) {
            if ($with) {
                $this->queryBetweenPeople($builder, [$person, $with]);
            } else {
                $this->queryForPerson($builder, $person);
            }

            if ($limit) {
                $this->paginate($builder, $limit, 1);
            }
        }));
    }


    /**
     *
     */
    public function findSince($since, Person $person, Person $with = NULL, $limit = NULL)
    {
        return $this->collect($this->query(function($builder) use ($since, $person, $with, $limit) {
            $since_message = $this->find($since);

            if ($with) {
                $this->queryBetweenPeople($builder, [$person, $with]);
            } else {
                $this->queryForPerson($builder, $person);
            }

            if ($limit) {
                $this->paginate($builder, $limit, 1);
            }

            $builder->andWhere('this.id != :since_message_id');
            $builder->andWhere('this.dateCreated >= :since_date_created');
            $builder->setParameter('since_message_id', $since_message->getId());
            $builder->setParameter('since_date_created', $since_message->getDateCreated());
            $builder->orderby('this.dateCreated', 'ASC');
        }));
    }


    /**
     *
     */
    public function getPeople()
    {
        return $this->people;
    }


    /**
     *
     */
    public function send(Message $message, Person ...$recipients)
    {
        foreach ($recipients as $recipient) {
            $receipt = $this->receipts->create();

            $receipt->setMessage($message);
            $receipt->setRecipient($recipient);

            $message->getReceipts()->add($receipt);
        }

        $this->store($message);
        $this->notifyRecipients($recipients);
    }


    /**
     *
     */
    public function notifyRecipients($recipients)
    {
        foreach ($recipients as $recipient) {
            if ($recipient->shouldReceiveChatNotifications()) {
                $recipient->notify(new NewChatMessage());
            }
        }
    }


    /**
     *
     */
    protected function queryBetweenPeople(QueryBuilder $builder, array $people = [])
    {
        $builder
            ->join('this.receipts', 'r')
            ->orderBy('this.dateCreated', 'DESC')
            ->where('r.recipient IN(:people)')
            ->andWhere('this.sender IN(:people)')
            ->setParameter('people', $people)
        ;
    }


    /**
     *
     */
    protected function queryForPerson(QueryBuilder $builder, Person $person)
    {
        $builder
            ->join('this.receipts', 'r')
            ->orderBy('this.dateCreated', 'DESC')
            ->where($builder->expr()->orX(
                $builder->expr()->eq('r.recipient', ':person'),
                $builder->expr()->eq('this.sender', ':person')
            ))
            ->setParameter('person', $person)
        ;
    }


    /**
     *
     */
    protected function queryToPerson(QueryBuilder $builder, Person $person, Person $with_person = NULL)
    {
        $builder
            ->join('this.receipts', 'r')
            ->orderBy('this.dateCreated', 'DESC')
            ->where('r.recipient = :person')
            ->setParameter('person', $person)
        ;

        if ($with_person) {
            $builder->where('this.sender = :sender');
            $builder->setParameter('sender', $with_person);
        }
    }
}
