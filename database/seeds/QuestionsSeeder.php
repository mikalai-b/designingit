<?php

use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    static protected $types = [
        'Yes / No' => [
            'template' => 'yes-no'
        ],
        'Yes / No (Note)' => [
            'template' => 'yes-no-note'
        ],
        'Short Answer' => [
            'template' => 'short-answer'
        ],
        'Acknowledgement' => [
            'template' => 'acknowledgement'
        ],
        'Understanding' => [
            'template' => 'understanding'
        ],
    ];

    static protected $questions = [
        [
            'type'     => 'Yes / No',
            'content'  => "Are you pregnant or planning on becoming pregnant in the near future?",
            'products' => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Yes / No (Note)',
            'content'  => "Do you take any prescription or non-prescription medicines?",
            'products' => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Yes / No (Note)',
            'content'  => "Are you allergic to any medications?",
            'products' => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Yes / No',
            'content'  => "Are you allergic to Bimatoprost (the active ingredient in Latisse)?",
            'products' => ['Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Understanding',
            'content'  => "I understand that I must stop Latisse one month prior to becoming pregnant and I cannot use it while pregnant or breastfeeding.",
            'products' => ['Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Yes / No',
            'content'  => "Do you have a history of eye problems, such as glaucoma, high eye pressure, macular edema, or an abnormality of the lens?",
            'products' => ['Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Acknowledgement',
            'content'  => "I have read and understand the possible side effects of Latisse, including a risk of irritation at the eyelid margin, eyelid pigmentation, and although uncommon, a possible risk of permanent discoloration of the iris of the eye.",
            'products' => ['Bimatoprost Ophthalmic Solution']
        ],
        [
            'type'     => 'Understanding',
            'content'  => "I understand that I must stop Tretinoin one month prior to becoming pregnant and I cannot use it while pregnant or breastfeeding.",
            'products' => ['Tretinoin cream']
        ],
        [
            'type'     => 'Yes / No (Note)',
            'content'  => "I would like to provide additional information / details for my dermatologist.",
            'products' => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(QuestionTypes $types, Questions $questions, ProductTypes $product_types, Products $products)
    {
        foreach (static::$types as $type_name => $details) {
            $type = $types->findOneByName($type_name);

            if (!$type) {
                $type = $types->create();

                $type->setName($type_name);
                $type->setTemplate($details['template']);
                $types->store($type, TRUE);
            }
        }

        $questions->reset();

        foreach (static::$questions as $order => $data) {
            $question = $questions->create();
            $type     = $types->findOneByName($data['type']);

            if (!$type) {
                throw Exception('Bad question type specified.');
            }

            $question->setType($type);
            $question->setDisplayOrder($order);
            $question->setContent($data['content']);

            foreach ($data['products'] as $ptype_name) {
                $product_type = $product_types->findOneByName($ptype_name);

                if (!$product_type) {
                    throw Exception('Bad product type specified.');
                }

                foreach ($product_type->getProducts() as $product) {
                    $product->getQuestions()->add($question);
                    $products->store($product, TRUE);
                }
            }
        }
    }
}
