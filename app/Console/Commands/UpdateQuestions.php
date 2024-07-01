<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Product;
use Products;
use Questions;
use QuestionTypes;

class UpdateQuestions extends Command
{
    const PREGNANT_LATISSE_QUESTION = 'I understand that I must stop Latisse one month before becoming pregnant, and I cannot use this product while pregnant or breastfeeding.';

    const PREGNANT_TRET_QUESTION = 'I understand that I must stop Tretinoin one month before becoming pregnant, and I cannot use this product while pregnant or breastfeeding.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:update-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch process for updating questions (April 2021)';

    /**
     * 
     */
    protected $questions;

    /**
     * 
     */
    protected $questionTypes;

    /**
     * 
     */
    protected $products;

    /**
     * 
     */
    protected $entityManager;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Questions $questions, QuestionTypes $questionTypes, EntityManager $entityManager, Products $products)
    {
        $this->questions = $questions;
        $this->questionTypes = $questionTypes;
        $this->products = $products;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->disablePregnantQuestion();
        $this->updatePregnantQuestions();
        $this->addConfigForAllergyQuestion();
        $this->addConfigForLatisseSideEffectsQuestion();
        $this->disableCurrentAllergiesQuestion();
        $this->addNewQuestions();
    }

    /**
     *
     */
    private function addConfigForAllergyQuestion() 
    {
        $allergyQuestion = $this->questions->query(function($query) {
            $query
                ->where('this.content LIKE ?1')
                ->setParameter(1, 'Are you allergic to Bimatoprost%')
            ;
        })->getResult()[0] ?? null;
        if (!$allergyQuestion) {
            $this->info('Could not find allergy question. Moving on.');
        } else {
            $allergyQuestion->setConfig([
                'no' => 'I <strong>do not</strong> have an allergy to Bimatoprost',
                'yes' => 'I am allergic to Bimatoprost',
            ]);
            $this->entityManager->flush();
        }
        
    }

    /**
     *
     */
    private function addConfigForLatisseSideEffectsQuestion() 
    {
        $question = $this->questions->query(function($query) {
            $query
                ->where('this.content LIKE ?1')
                ->setParameter(1, 'I have read and understand the possible side effects of Latisse%')
            ;
        })->getResult()[0] ?? null;
        if (!$question) {
            $this->info('Could not find side effects question. Moving on.');
        } else {
            $question->setConfig([
                'no' => 'No, I am not ok with the possible side effects of using this product ',
                'yes' => '<strong>I acknowledge and understand</strong> the possible side effects of using this product ',
            ]);
            $this->entityManager->flush();
        }
        
    }

    /**
     *
     */
    private function disablePregnantQuestion()
    {
        $pregnantQuestion = $this->questions->query(function($query) {
            $query
                ->where('this.content LIKE ?1')
                ->setParameter(1, 'Are you pregnant%')
            ;
        })->getResult()[0] ?? null;
        if (!$pregnantQuestion) {
            $this->info('Could not find pregnant question. Moving on.');
        } else {
            $pregnantQuestion->setActive(0);
            $this->entityManager->flush();
        }
    }

    /**
     *
     */
    private function disableCurrentAllergiesQuestion()
    {
        $question = $this->questions->query(function($query) {
            $query
                ->where('this.content = ?1')
                ->setParameter(1, 'Are you allergic to any medications?')
            ;
        })->getResult()[0] ?? null;
        if (!$question) {
            $this->info('Could not find current allergies question. Moving on.');
        } else {
            $question->setActive(0);
            $this->entityManager->flush();
        }
    }

    /**
     *
     */
    private function updatePregnantQuestions()
    {
        $latisseQuestion = $this->questions->find(5);
        if (preg_match('/^I understand that I must stop Latisse/', $latisseQuestion->getContent())) {
            $latisseQuestion->setContent(static::PREGNANT_LATISSE_QUESTION);
        } else {
            $this->info('Could not find Latisse question. Moving on.');
        }
        $tretinoinQuestion = $this->questions->find(8);
        if (preg_match('/^I understand that I must stop Tretinoin/', $tretinoinQuestion->getContent())) {
            $tretinoinQuestion->setContent(static::PREGNANT_TRET_QUESTION);
        } else {
            $this->info('Could not find Tretinoin question. Moving on.');
        }
        $this->entityManager->flush();
    }

    /**
     * 
     */
    private function addNewQuestions()
    {
        $allProducts = $this->products->findAll();
        $newQuestions = [
            [
                'type' => 3,
                'content' => 'Please list all your prior and current medical problems and surgeries.',
                'display_order' => 2,
                'active' => 1,
                'products' => $allProducts,
            ],
            [
                'type' => 3,
                'content' => 'Please list all allergies that you have, including allergies to any medications or any supplements, and all other allergies.',
                'display_order' => 2,
                'active' => 1,
                'products' => $allProducts,
            ],
        ];
        foreach ($newQuestions as $questionData) {
            $check = $this->questions->findOneByContent($questionData['content']);
            if (!$check) {
                $question = $this->questions->create();
                $question->setType($this->questionTypes->find($questionData['type']));
                $question->setContent($questionData['content']);
                $question->setDisplayOrder($questionData['display_order']);
                $question->setActive($questionData['active']);
                $this->questions->store($question, true);
                foreach ($questionData['products'] as $product) {
                    $question->addProduct($product);
                }
                $this->entityManager->flush();
            } else {
                $this->info('Already found question. Moving on.');
            }
        }
    }

}
