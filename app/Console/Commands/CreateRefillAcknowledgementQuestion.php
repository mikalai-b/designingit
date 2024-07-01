<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Products;
use Question;
use Questions;
use QuestionTypes;

class CreateRefillAcknowledgementQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:create-refill-acknowledgement-question';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $entityManager;

    private $questions;

    private $products;

    private $questionTypes;

    private $question1;

    private $questionMultiple;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EntityManager $entityManager,
        Questions $questions,
        QuestionTypes $questionTypes,
        Products $products
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->questions = $questions;
        $this->questionTypes = $questionTypes;
        $this->products = $products;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->addNewQuestions();
        $this->assignNewQuestionsToProducts();
    }

    private function addNewQuestions()
    {
        $this->info('Adding new questions.');

        $question1Content = 'I understand that the prescription will ship every [[product.friendly_default_period]]. Refills can be adjusted at any time by clicking refill settings in my dashboard.';

        $questionMultipleContent = 'I understand that the prescription can be adjusted between [[product.period_count]] refill options or paused or stopped at any time. The initial default refill frequency is every [[product.friendly_default_period]]. Refills can be adjusted at any time by clicking refill settings in my dashboard.';

        $questionConfig = [
            'yes' => 'I understand',
            'no' => 'I do not understand',
        ];

        $this->question1 = $this->questions->findOneBy([
            'content' => $question1Content,
        ]);

        if ($this->question1) {
            $this->info('Question 1 already exists. Moving on.');
        } else {
            $this->question1 = new Question();
            $this->question1->setType($this->questionTypes->findOneBy(['name'=>'Acknowledgement']));
            $this->question1->setContent($question1Content);
            $this->question1->setDisplayOrder(99);
            $this->question1->setActive(1);
            $this->question1->setConfig($questionConfig);
            $this->questions->store($this->question1, true);
        }

        $this->questionMultiple = $this->questions->findOneBy([
            'content' => $questionMultipleContent,
        ]);

        if ($this->questionMultiple) {
            $this->info('Question multiple already exists. Moving on.');
        } else {
            $this->questionMultiple = new Question();
            $this->questionMultiple->setType($this->questionTypes->findOneBy(['name'=>'Acknowledgement']));
            $this->questionMultiple->setContent($questionMultipleContent);
            $this->questionMultiple->setDisplayOrder(99);
            $this->questionMultiple->setActive(1);
            $this->questionMultiple->setConfig($questionConfig);
            $this->questions->store($this->questionMultiple, true);
        }
    }

    private function assignNewQuestionsToProducts()
    {
        $this->info('Assigning questions to products.');
        $products = $this->products->findAll();
        foreach ($products as $product) {
            $this->info(sprintf('Working on product %s', $product->getId()));
            $availablePeriods = $product->getAvailableDashboardPeriods();
            if (count($availablePeriods) > 1) {
                $this->questionMultiple->addProduct($product);
            } else {
                $this->question1->addProduct($product);
            }
        }
        $this->entityManager->flush();
    }
}
