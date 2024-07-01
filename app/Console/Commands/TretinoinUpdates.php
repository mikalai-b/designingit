<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Doctrine\ORM\EntityManager;
use Product;
use Products;
use ProductTypes;
use Question;
use Questions;
use QuestionTypes;

class TretinoinUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:tretinoin-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes updates to Tretinoin product line.';

    /**
     * 
     */
    protected $entityManager;

    /**
     * 
     */
    protected $products;

    /**
     * 
     */
    protected $productTypes;

    /**
     * 
     */
    protected $questions;

    /**
     * 
     */
    protected $questionTypes;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EntityManager $entityManager,
        Products $products,
        ProductTypes $productTypes,
        Questions $questions,
        QuestionTypes $questionTypes
    )
    {
        $this->entityManager = $entityManager;
        $this->products = $products;
        $this->productTypes = $productTypes;
        $this->questions = $questions;
        $this->questionTypes = $questionTypes;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        $this->renameObagi();
        $this->cloneProductType();
        $this->addAdditionalStrength();
    }

    private function renameObagi()
    {
        $updates = [
            'Obagi-0.025%' => [
                'thumbnail' => '/images/thumbnails/retin-a-025.jpg'
            ],
            'Obagi-0.1%' => [
                'thumbnail' => '/images/thumbnails/retin-a-100.jpg'
            ],
            'Obagi-0.05%' => [
                'thumbnail' => '/images/thumbnails/retin-a-050.jpg'
            ],
        ];
        $this->info('Renaming Obagi...');
        foreach ($updates as $product => $attributes) {
            list($name, $strength) = preg_split('/\-/', $product);
            
            $record = $this->products->findOneBy([
                'name' => $name,
                'strength' => $strength,
            ]);
            if ($record) {
                $record->setName('Retin-A');
                $record->setThumbnail($attributes['thumbnail']);
                $record->setPrice(59);
                $record->setGrouponPrice(59);
                $this->products->store($record, true);
            }
        }
    }

    private function cloneProductType()
    {
        $this->info('Cloning product type.');
        $cream = $this->productTypes->findOneBy([
            'name' => 'Tretinoin cream',
        ]);
        $lotion = clone $cream;
        $lotion->setName('Tretinoin lotion');
        $this->productTypes->store($lotion, true);
    }

    private function addAdditionalStrength()
    {
        $this->info('Adding additional strength.');
        $otherProduct = $this->products->findOneBy([
            'ndcNumber' => '62032-0412-20'
        ]);

        $attributes = [
            'name' => 'Altreno',
            'strength' => '0.05%',
            'info' => 'For Normal Skin',
            'ndc_number' => '0187-0005-20',
            'price' => '59',
            'groupon_price' => '59',
            'type' => 2,
            'quantity' => '20g',
            'thumbnail' => '/images/thumbnails/altreno-050.jpg',
        ];

        $product = new Product();
        $product->setName($attributes['name']);
        $product->setStrength($attributes['strength']);
        $product->setInfo($attributes['info']);
        $product->setNdcNumber($attributes['ndc_number']);
        $product->setPrice($attributes['price']);
        $product->setGrouponPrice($attributes['groupon_price']);
        $product->setType($this->productTypes->findOneBy([
            'name' => 'Tretinoin lotion',
        ]));
        $product->setQuantity($attributes['quantity']);
        $product->setPrescriptionOnly(1);
        $product->setThumbnail($attributes['thumbnail']);
        $product->setQuestions($otherProduct->getQuestions());
        $product->setPhotoTypes($otherProduct->getPhotoTypes());

        $question = new Question();
        $question->setContent('Do you have an allergy to fish?');
        $question->setConfig([
            'yes' => 'I do have an allergy to fish.',
            'no' => 'I do NOT have an allergy to fish.',
        ]);
        $question->setDisplayOrder(3);
        $question->setType($this->questionTypes->findOneBy(['name' => 'Yes / No']));
        $question->setActive(1);
        $this->questions->store($question, true);
        $product->addQuestion($question);

        $this->products->store($product, true);
        $product->addCouponCodes($otherProduct->getCouponCodes());
        $this->entityManager->flush();        
    }
}
