<?php

namespace App\Console\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use PhotoTypes;
use Products;
use ProductTypes;
use Questions;
use QuestionTypes;
use Symptoms;

class SetupValacyclovir extends Command
{
    const TYPE_NAME = 'Generic Valtrex®';

    const TYPE_DIRECTIONS = 'Take 1 tablet by mouth daily.';
    const TYPE_CONSENT_FORM = null;
    const TYPE_REQUIRE_AUTO_RENEWAL = 1;
    const TYPE_APPROVED_TEMPLATE = '<p>I am sending the prescription to the pharmacists now, and they will ship you your prescription within 24 hours.</p><p>Valacyclovir (generic Valtrex®) is a prescription medication that can be taken daily in this strength (500mg) to prevent herpes simplex outbreaks.</p><p>If you have any questions now or in the future, please do not hesitate to send me a message through your patient portal at CosmeticRx (all information is always HIPAA-encrypted and secure).</p>';
    const TYPE_DEFAULT_REFILLS = 11;
    const TYPE_DEFAULT_EXPIRATION = 12;
    const TYPE_DEFAULT_PERIOD = 90;
    const TYPE_AVAILABLE_PERIODS = ['90'];
    const PRODUCT_ID = 'b18a5c42-aae1-11ec-b909-0242ac120002';
    const PRODUCT_NAME = 'Valacyclovir';
    const PRODUCT_STRENGTH = '500mg';
    const PRODUCT_SLUG = 'valacyclovir-500mg-90-pills';
    const PRODUCT_QUANTITY = '90 ct';
    const PRODUCT_INFO = 'For cold sores or genital herpes outbreaks';
    const PRODUCT_PRICE = 90;
    const PRODUCT_NDC_NUMBER = null;
    const PRODUCT_THUMBNAIL = '/images/thumbnails/valacyclovir.jpg';

    const QUESTIONS = [
        [
            'content' => 'Have you been diagnosed with having cold sores previously by a doctor or other clinician?',
            'type' => 'yes-no',
        ],
        [
            'content' => 'Have you been diagnosed with having genital herpes previously by a doctor or other clinician?',
            'type' => 'yes-no',
        ],
        [
            'content' => 'How many cold sore outbreaks do you have a year on average?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'None',
                    'Less than 3',
                    '3-7',
                    'Over 7',
                ],
                'multi' => 0,
            ],
        ],
        [
            'content' => 'How many genital outbreaks do you have a year on average?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'None',
                    'Less than 3',
                    '3-7',
                    'Over 7',
                ],
                'multi' => 0,
            ],
        ],
        [
            'content' => 'Please list any of the following medications from this list that you take? (Cladrabine, Clozapine, Foscarnet, Mycophenolate, Talimogene laherparepvec, Tenofovir products, Theophylline derivatives, Tizanide, Zidovudine)',
            'type' => 'yes-no-note',
            'config' => [
                'yes' => 'Yes, I take 1 or more of these medications',
                'no' => 'No, I do not take any of these medications',
            ],
        ],
        [
            'content' => 'Please list any of the following conditions that you have from this list? (weakened immune system not related to HIV, pregnant or breastfeeding, aseptic encephalitis or transverse myelitis, urinary retention issues, seizures, kidney transplant or other kidney problems, bone marrow transplant)',
            'type' => 'yes-no-note',
            'config' => [
                'yes' => 'Yes, I have 1 or more of these conditions',
                'no' => 'No, I do not have any of these conditions',
            ],
        ],
        [
            'content' => 'Please list in the box all your current medications, vitamins, and/or supplements (if none, you can write none or N/A)',
            'type' => 'short-answer',
        ],
        [
            'content' => 'Please list in the box any allergies that you have (if none, you can write none, or N/A)',
            'type' => 'short-answer',
        ],
        [
            'content' => 'Please list in the box any current or past medical problems and surgeries (if none, you can write none, or N/A)',
            'type' => 'short-answer',
        ],
        [
            'content' => 'Valacyclovir is generally very well-tolerated. Approximately 1/10 individuals may experience either nausea or stomach pain or headache. I understand that I will stop the medication and contact my physician if I experience of any side effect including: fever, pale skin, red or pink urine, painful urination, or reduced urine volume, feeling weak or tired, stomach pain, bloody diarrhea, vomiting, swelling in feet, hands, or feet, confusion or feeling unsteady, hallucinations, or problems with speech.',
            'type' => 'acknowledgement',
            'config' => [
                'yes' => 'Yes, I acknowledge the potential side effects of taking Valacyclovir and will stop this medication and contact my physician if I experience a side effect',
                'no' => 'No, I do not acknowledge the potential side effects of taking Valacyclovir',
            ],
        ],
    ];

    const SYMPTOM_ID = 'B00';
    const SYMPTOM_CONTENT = 'Herpesviral [herpes simplex] infections';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:setup-valacyclovir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process for setting up Valacyclovir in the DB.';

    /**
     * 
     */
    protected $productTypes;

    /**
     * 
     */
    protected $products;

    /**
     * 
     */
    protected $questionTypes;

    /**
     * 
     */
    protected $questions;

    /**
     * 
     */
    protected $photoTypes;

    /**
     * 
     */
    protected $symptoms;

    /**
     * 
     */
    protected $entityManager;

    /**
     * 
     */
    protected $productType;

    /**
     * 
     */
    protected $product;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductTypes $productTypes,
        Products $products,
        QuestionTypes $questionTypes,
        Questions $questions,
        PhotoTypes $photoTypes,
        Symptoms $symptoms,
        EntityManager $entityManager
    ) {
        $this->productTypes = $productTypes;
        $this->products = $products;
        $this->questionTypes = $questionTypes;
        $this->questions = $questions;
        $this->photoTypes = $photoTypes;
        $this->symptoms = $symptoms;
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
        $this->addNewQuestionTypes();
        $this->addNewProductType();
        $this->addNewProduct();
        $this->addNewQuestions();
        $this->setPhotoTypesForNewProduct();
        $this->addSymptoms();
    }


    private function addNewQuestionTypes()
    {
        $this->info('Adding new question types...');
        $result = $this->questionTypes->findOneBy([
            'template' => 'multiple-choice',
        ]);
        if (!$result) {
            $questionType = $this->questionTypes->create();
            $questionType->setName('Multiple Choice');
            $questionType->setTemplate('multiple-choice');
            $this->questionTypes->store($questionType, true);
        } else {
            $this->info('Got question type already!');
        }
    }

    private function addNewProductType()
    {
        $this->info('Adding new product type...');
        $this->productType = $this->productTypes->findOneBy([
            'name' => static::TYPE_NAME,
        ]);

        if (!$this->productType) {
            $type = $this->productTypes->create();
            $type->setName(static::TYPE_NAME);
            $type->setDirections(static::TYPE_DIRECTIONS);
            $type->setConsentForm(static::TYPE_CONSENT_FORM);
            $type->setInstructionsTemplate(null);
            $type->setApprovedTemplate(static::TYPE_APPROVED_TEMPLATE);
            $type->setDeclinedTemplate(null);
            $type->setDefaultRefills(static::TYPE_DEFAULT_REFILLS);
            $type->setDefaultExpiration(static::TYPE_DEFAULT_EXPIRATION);
            $type->setDefaultPeriod(static::TYPE_DEFAULT_PERIOD);
            $type->setAvailablePeriods(static::TYPE_AVAILABLE_PERIODS);
            $type->setAvailableDashboardPeriods(static::TYPE_AVAILABLE_PERIODS);
            $type->setRequireAutoRenewal(static::TYPE_REQUIRE_AUTO_RENEWAL);
            $this->productType = $type;
            $this->productTypes->store($this->productType, true);
        } else {
            $this->info('Got product type already!');
        }
    }

    private function addNewProduct()
    {
        $this->info('Adding new product...');
        $this->product = $this->products->findOneBy([
            'name' => static::PRODUCT_NAME,
        ]);

        if (!$this->product) {
            $product = $this->products->create();
            $product->setId(static::PRODUCT_ID);
            $product->setType($this->productType);
            $product->setPrescriptionOnly(1);
            $product->setName(static::PRODUCT_NAME);
            $product->setSlug(static::PRODUCT_SLUG);
            $product->setStrength(static::PRODUCT_STRENGTH);
            $product->setQuantity(static::PRODUCT_QUANTITY);
            $product->setInfo(static::PRODUCT_INFO);
            $product->setPrice(static::PRODUCT_PRICE);
            $product->setNdcNumber(static::PRODUCT_NDC_NUMBER);
            $product->setThumbnail(static::PRODUCT_THUMBNAIL);
            $product->setGrouponPrice(static::PRODUCT_PRICE);
            $product->setAvailablePeriods(null);
            $product->setAvailableDashboardPeriods(null);
            $this->product = $product;
            $this->products->store($this->product, true);
        } else {
            $this->info('Got product already!');
        }
    }

    private function addNewQuestions()
    {
        $this->info('Adding new questions...');
        $newQuestions = new ArrayCollection();
        foreach (static::QUESTIONS as $key => $questionArray) {
            $question = $this->questions->create();
            $question->setDisplayOrder($key + 1);
            $question->setContent($questionArray['content']);
            $question->setActive(1);
            $question->setType($this->questionTypes->findOneBy([
                'template' => $questionArray['type'],
            ]));
            if ($questionArray['config'] ?? false) {
                $question->setConfig($questionArray['config']);
            }
            $this->questions->store($question, true);
            $newQuestions->add($question);
        }
        $this->product->setQuestions($newQuestions);
        $this->entityManager->flush();
    }

    private function setPhotoTypesForNewProduct()
    {
        $photoTypes = $this->photoTypes->findBy([
            'name' => [
                'Face Shot',
                'Upload a photo of your ID',
            ],
        ]);
        $this->product->setPhotoTypes($photoTypes);
        $this->entityManager->flush();
    }

    private function addSymptoms()
    {
        $this->info('Adding new symptom...');
        $symptom = $this->symptoms->findOneBy([
            'id' => static::SYMPTOM_ID,
        ]);
        if (!$symptom) {
            $symptom = $this->symptoms->create();
            $symptom->setId(static::SYMPTOM_ID);
            $symptom->setContent(static::SYMPTOM_CONTENT);
            $this->symptoms->store($symptom, true);
        }

        $this->productType->setSymptoms(new ArrayCollection([$symptom]));
        $this->entityManager->flush();
    }

}
