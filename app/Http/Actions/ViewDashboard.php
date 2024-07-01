<?php

namespace App\Http\Actions;

use Orders;
use Prescriptions;
use Consultations;
use CreditCards;
use Messages;
use Illuminate\Support\Str;
use ProductCategories;
use Products;
use ProductTypes;
use Symptoms;

/**
 *
 */
class ViewDashboard extends AbstractAction
{
    static protected $two_products_types = [
        'Semaglutide' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved template text here!</p>
EOT
        ],
        'Tirzepatide' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved template text here!</p>
EOT
        ]
    ];

    static protected $two_mental_products = [
        'Semaglutide' => [
            [
                'name' => 'Semaglutide',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Semaglutide.png',
                'ndcNumber' => null
            ],
        ],
        'Tirzepatide' => [
            [
                'name' => 'Tirzepatide',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 499,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Tirzepatide.png',
                'ndcNumber' => null
            ],
        ],
    ];

    static protected $mentah_health_types = [
        'Escitalopram' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Bupropion XL' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Sertraline' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Fluoxetine' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Citalopram' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Paroxetine' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Buspirone' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Venlafaxine' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
        'Miratazapine' => [
            'defaultRefills' => 1,
            'defaultExpiration' => 1,
            'defaultPeriod' => 30,
            'availablePeriods' => [30],
            'requireAutoRenewal' => false,
            'consentForm' => null,
            'symptoms' => [],
            'directions' => 'Directions here',
            'declinedTemplate' => null,
            'approvedTemplate' => <<<EOT
                    <p>Approved tempolate text here!</p>
EOT
        ],
    ];

    static protected $two_semaglutide_products = [
        'Semaglutide' => [
            [
                'name' => 'Semaglutide',
                'prescriptionOnly' => TRUE,
                'strength' => "0.5mg",
                'quantity' => "2.5mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Semaglutide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Semaglutide',
                'prescriptionOnly' => TRUE,
                'strength' => "1mg",
                'quantity' => "5mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Semaglutide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Semaglutide',
                'prescriptionOnly' => TRUE,
                'strength' => "1.7mg",
                'quantity' => "10mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Semaglutide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Semaglutide',
                'prescriptionOnly' => TRUE,
                'strength' => "2.4mg",
                'quantity' => "10mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Semaglutide.png',
                'ndcNumber' => null
            ],
        ]
    ];

    static protected $two_tirzepatide_products = [
        'Semaglutide' => [
            [
                'name' => 'Tirzepatide',
                'prescriptionOnly' => TRUE,
                'strength' => "0.5mg",
                'quantity' => "2.5mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Tirzepatide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Tirzepatide',
                'prescriptionOnly' => TRUE,
                'strength' => "1mg",
                'quantity' => "5mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Tirzepatide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Tirzepatide',
                'prescriptionOnly' => TRUE,
                'strength' => "1.7mg",
                'quantity' => "10mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Tirzepatide.png',
                'ndcNumber' => null
            ],[
                'name' => 'Tirzepatide',
                'prescriptionOnly' => TRUE,
                'strength' => "2.4mg",
                'quantity' => "10mg",
                'info' => null,
                'price' => 299,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Tirzepatide.png',
                'ndcNumber' => null
            ],
        ]
    ];

    static protected $mental_health_products = [
        'Escitalopram' => [
            [
                'name' => 'Escitalopram',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Escitalopram.png',
                'ndcNumber' => null
            ],
        ],
        'Bupropion XL' => [
            [
                'name' => 'Bupropion XL',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Bupropion XL.png',
                'ndcNumber' => null
            ],
        ],
        'Sertraline' => [
            [
                'name' => 'Sertraline',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Sertraline',
                'ndcNumber' => null
            ],
        ],
        'Fluoxetine' => [
            [
                'name' => 'Fluoxetine',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Fluoxetine',
                'ndcNumber' => null
            ],
        ],
        'Citalopram' => [
            [
                'name' => 'Citalopram',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Citalopram',
                'ndcNumber' => null
            ],
        ],
        'Paroxetine' => [
            [
                'name' => 'Paroxetine',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Paroxetine',
                'ndcNumber' => null
            ],
        ],
        'Buspirone' => [
            [
                'name' => 'Buspirone',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Buspirone',
                'ndcNumber' => null
            ],
        ],
        'Venlafaxine' => [
            [
                'name' => 'Venlafaxine',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Venlafaxine',
                'ndcNumber' => null
            ],
        ],
        'Miratazapine' => [
            [
                'name' => 'Miratazapine',
                'prescriptionOnly' => TRUE,
                'strength' => null,
                'quantity' => null,
                'info' => null,
                'price' => 30,
                'grouponPrice' => null,
                'thumbnail' => '/images/thumbnails/mental-health-images/Miratazapine',
                'ndcNumber' => null
            ],
        ]
    ];


    /**
     *
     */
    public function __invoke(Orders $orders, Consultations $consultations, Prescriptions $prescriptions, Messages $messages, CreditCards $creditCards)
    {
        $user = $this->auth->user();

        return $this->render('pages.dashboard.index', 200, get_defined_vars());
    }

    public function insertNewMentalProducts(ProductTypes $types, Products $products, Symptoms $symptoms, ProductCategories $productCategories)
    {
        $mentalHealthProductCategory = $productCategories->findBy(['slug' => 'mental-health'])->first();
        if(!$mentalHealthProductCategory) {
            dd("Mental health category not exist! First, create it!");
        }

        foreach (static::$mentah_health_types as $type_name => $type_data) {
            $type = $types->findOneByName($type_name);

            if (!$type) {
                $type = $types->create();

                $type->setName($type_name);
            } else {
                $type->getSymptoms()->clear();
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

            foreach ($type_data['symptoms'] as $symptom_id) {
                $symptom = $symptoms->find($symptom_id);

                if ($symptom) {
                    $type->getSymptoms()->add($symptom);
                }
            }

            $types->store($type, TRUE);

            if (isset(static::$mental_health_products[$type_name])) {
                foreach (static::$mental_health_products[$type_name] as $item) {
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
                    $product->setCategory($mentalHealthProductCategory);
                    $product->setGrouponPrice($item['grouponPrice']);
                    $product->setThumbnail($item['thumbnail']);
                    $product->setType($type);
                    $product->setNdcNumber($item['ndcNumber']);

                    $slug = Str::slug($item['name'] . ' ' . $item['strength'] . ' ' . $item['quantity'], '-', 'en');
                    $product->setSlug($slug ?? null);

                    $products->store($product, true);
                }
            }
        }

        dd("Done! New mental products added!");
    }

    public function insertTwoMentalProducts(ProductTypes $types, Products $products, Symptoms $symptoms, ProductCategories $productCategories)
    {
        $mentalHealthProductCategory = $productCategories->findBy(['slug' => 'mental-health'])->first();
        if(!$mentalHealthProductCategory) {
            dd("Mental health category not exist! First, create it!");
        }

        foreach (static::$two_products_types as $type_name => $type_data) {
            $type = $types->findOneByName($type_name);

            if (!$type) {
                $type = $types->create();

                $type->setName($type_name);
            } else {
                $type->getSymptoms()->clear();
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

            foreach ($type_data['symptoms'] as $symptom_id) {
                $symptom = $symptoms->find($symptom_id);

                if ($symptom) {
                    $type->getSymptoms()->add($symptom);
                }
            }

            $types->store($type, TRUE);

            if (isset(static::$two_mental_products[$type_name])) {
                foreach (static::$two_mental_products[$type_name] as $item) {
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
                    $product->setCategory($mentalHealthProductCategory);
                    $product->setGrouponPrice($item['grouponPrice']);
                    $product->setThumbnail($item['thumbnail']);
                    $product->setType($type);
                    $product->setNdcNumber($item['ndcNumber']);

                    $slug = Str::slug($item['name'] . ' ' . $item['strength'] . ' ' . $item['quantity'], '-', 'en');
                    $product->setSlug($slug ?? null);

                    $products->store($product, true);
                }
            }
        }

        dd("Done! New mental products added!");
    }

    public function updateSemaglutide(ProductTypes $types, Products $products, ProductCategories $productCategories)
    {
        $type = $types->findOneByName('Semaglutide');
        $existSemaglutideProduct = $products->find("e1ffaa18-9f95-49ee-9403-2fc8baa06dda");

        $existSemaglutideProduct->setName('Semaglutide');
        $existSemaglutideProduct->setQuantity('2.5mg');
        $existSemaglutideProduct->setStrength('0.25mg');
        $products->store($existSemaglutideProduct, true);

        $dermahProductCategory = $productCategories->findBy(['slug' => 'derma'])->first();

        foreach (static::$two_semaglutide_products['Semaglutide'] as  $item) {
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
            $product->setCategory($dermahProductCategory);
            $product->setGrouponPrice($item['grouponPrice']);
            $product->setThumbnail($item['thumbnail']);
            $product->setType($type);
            $product->setNdcNumber($item['ndcNumber']);

            $slug = Str::slug($item['name'] . ' ' . $item['strength'] . ' ' . $item['quantity'], '-', 'en');
            $product->setSlug($slug ?? null);

            $products->store($product, true);
        }

        dd('done');
    }

    public function updateTirzepatide(ProductTypes $types, Products $products, ProductCategories $productCategories)
    {
        $type = $types->findOneByName('Tirzepatide');
        $existTirzepatideProduct = $products->find("9c3cc7d2-259a-41f2-b002-9a78b239d9c9");

        $existTirzepatideProduct->setName('Tirzepatide');
        $existTirzepatideProduct->setQuantity('2.5mg');
        $existTirzepatideProduct->setStrength('0.25mg');
        $products->store($existTirzepatideProduct, true);

        $dermahProductCategory = $productCategories->findBy(['slug' => 'derma'])->first();

        foreach (static::$two_tirzepatide_products['Semaglutide'] as  $item) {
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
            $product->setCategory($dermahProductCategory);
            $product->setGrouponPrice($item['grouponPrice']);
            $product->setThumbnail($item['thumbnail']);
            $product->setType($type);
            $product->setNdcNumber($item['ndcNumber']);

            $slug = Str::slug($item['name'] . ' ' . $item['strength'] . ' ' . $item['quantity'], '-', 'en');
            $product->setSlug($slug ?? null);

            $products->store($product, true);
        }

        dd('done');
    }
}
