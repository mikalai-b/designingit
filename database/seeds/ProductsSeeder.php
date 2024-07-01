<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    static protected $types = [
        'Bimatoprost Ophthalmic Solution' => [
            'defaultRefills' => 11,
            'defaultExpiration' => 11,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => true,
            'consentForm' => '/latisse-consent-form',
            'symptoms' => ['L65.9'],
            'directions' => 'Use applicator to apply nightly to the skin of upper eyelid margin (base of eyelashes). Blot excess beyond eyelid margin.',
            'declinedTemplate' => <<<EOT

EOT
,
            'approvedTemplate' => <<<EOT
                <p>
                    {{ product.name }} is a medication to help increase the length and fullness of your eyelashes. You should start seeing results after 4 weeks of use.
                </p>
                <p>
                    Most patients tolerate {{ product.name }} very well. All medications can cause side effects, however, and patients should be aware of possible side effects from using {{ product.name }}.
                </p>
                <p>
                    {{ product.name }} solution use may cause darkening of the eyelid skin which may be reversible. {{ product.name }} use may cause increased brown iris pigmentation of the colored part of the eye which is likely to be permanent. While very infrequent, increased iris pigmentation has occurred when {{ product.name }} was administered.
                </p>
                <p>
                    The most common side effects after using {{ product.name }} are an itching sensation in the eyes and/or eye redness, which were reported in approximately 4% of clinical trial patients. {{ product.name }} solution may cause other less common side effects which typically occur close to where {{ product.name }} is applied. These potential side effects include skin darkening, eye irritation, dryness of the eyes and redness of the eyelids.
                </p>
                <p>
                    It is possible for hair growth to occur in other areas of your skin that {{ product.name }} frequently touches. Any excess solution outside the upper eyelid margin should be blotted with a tissue or other absorbent material to reduce the chance of this happening.
                </p>
                <p>
                    Correct application of {{ product.name }} is very important. There is also <a href="http://www.latisse.com/HowToApply.aspx">a great video on the {{ product.name }} website</a> - which shows a patient applying {{ product.name }} correctly.
                </p>
EOT
        ],
        'Tretinoin cream' => [
            'defaultRefills' => 6,
            'defaultExpiration' => 6,
            'defaultPeriod' => 90,
            'availablePeriods' => [90],
            'requireAutoRenewal' => false,
            'consentForm' => '/tretinoin-consent-form',
            'symptoms' => ['L98.8'],
            'directions' => 'Apply a pea-sized amount to the face at night.  This should be applied 30 minutes after washing the face.',
            'declinedTemplate' => <<<EOT

EOT
,
            'approvedTemplate' => <<<EOT
                <p>
                    {{ product.name }} is used to treat wrinkles and fine lines.
                </p>
                <p>
                    Correct application of the {{ product.name }} will reduce the potential side effect of "flaky" skin. You should wash your face at night with a gentle face cleanser (I like Cetaphil Facial Cleanser). You should then wait a full 30 minutes before applying a pea-sized amount of {{ product.name }}. Two of the biggest mistakes I see with {{ product.name }} is that patients use too much, and apply it shortly after washing the face. {{ product.name }} works incredibly well, although you will only start to see the results after4 weeks.
                </p>
                <p>
                    You should stop {{ product.name }} at least 2 weeks before any cosmetic treatment of any kind in the future. You should also stop {{ product.name }}before becoming pregnant and it cannot be use during pregnancy.
                </p>
EOT
        ]
    ];

    static protected $products = [
        'Bimatoprost Ophthalmic Solution' => [
            [
                'name' => 'Latisse',
                'prescriptionOnly' => TRUE,
                'strength' => '0.03%',
                'quantity' => '3ml',
                'info' => 'For Inadequate Lashes',
                'price' => 110.00,
                'thumbnail' => '/images/thumbnails/latisse-003-square.jpg',
                'ndcNumber' => '00023-3616-70'
            ],
        ],
        'Tretinoin cream' => [
            [
                'name' => 'Renova',
                'prescriptionOnly' => TRUE,
                'strength' => '0.02%',
                'quantity' => '20g',
                'info' => 'For Normal Skin',
                'price' => 90.00,
                'thumbnail' => '/images/thumbnails/renova.jpg',
                'ndcNumber' => '0062-0185-00'
            ],
            [
                'name' => 'Obagi',
                'prescriptionOnly' => TRUE,
                'strength' => '0.025%',
                'quantity' => '20g',
                'info' => 'For Normal Skin',
                'price' => 80.00,
                'thumbnail' => '/images/thumbnails/obagi-025.jpg',
                'ndcNumber' => '62032-0414-20'
            ],
            [
                'name' => 'Obagi',
                'prescriptionOnly' => TRUE,
                'strength' => '0.05%',
                'quantity' => '20g',
                'info' => 'For Oily Skin',
                'price' => 85.00,
                'thumbnail' => '/images/thumbnails/obagi-050.jpg',
                'ndcNumber' => '62032-0412-20'
            ],
            [
                'name' => 'Obagi',
                'prescriptionOnly' => TRUE,
                'strength' => '0.1%',
                'quantity' => '20g',
                'info' => 'For Very Oily Skin',
                'price' => 85.00,
                'thumbnail' => '/images/thumbnails/obagi-100.jpg',
                'ndcNumber' => '62032-0417-20'
            ],
        ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ProductTypes $types, Products $products, Symptoms $symptoms)
    {
        foreach (static::$types as $type_name => $type_data) {
            $type = $types->findOneByName($type_name);

            if (!$type) {
                $type = $types->create();

                $type->setName($type_name);
            }

            $type->setDirections($type_data['directions'] ?? NULL);
            $type->setDefaultRefills($type_data['defaultRefills'] ?? NULL);
            $type->setDefaultExpiration($type_data['defaultExpiration'] ?? NULL);
            $type->setDefaultPeriod($type_data['defaultPeriod'] ?? NULL);
            $type->setRequireAutoRenewal($type_data['requireAutoRenewal'] ?? FALSE);
            $type->setConsentForm($type_data['consentForm'] ?? NULL);
            $type->setInstructionsTemplate($type_data['instructionsTemplate'] ?? NULL);
            $type->setApprovedTemplate($type_data['approvedTemplate'] ?? NULL);
            $type->setAvailablePeriods($type_data['availablePeriods'] ?? NULL);
            $type->setDeclinedTemplate($type_data['declinedTemplate'] ?? NULL);

            $type->setSymptoms(NULL);

            foreach ($type_data['symptoms'] as $symptom_id) {
                $symptom = $symptoms->find($symptom_id);

                if ($symptom) {
                    $type->getSymptoms()->add($symptom);
                }
            }

            $types->store($type, TRUE);

            if (isset(static::$products[$type_name])) {
                foreach (static::$products[$type_name] as $item) {
                    $product = $products->findOneBy(array_slice($item, 0, 4, TRUE));

                    if (!$product) {
                        $product = $products->create();
                    }

                    $product->setName($item['name']);
                    $product->setPrescriptionOnly($item['prescriptionOnly']);
                    $product->setStrength($item['strength']);
                    $product->setQuantity($item['quantity']);
                    $product->setInfo($item['info']);
                    $product->setPrice($item['price']);
                    $product->setThumbnail($item['thumbnail']);
                    $product->setType($type);
                    $product->setNdcNumber($item['ndcNumber']);

                    $products->store($product);
                }
            }
        }
    }
}
