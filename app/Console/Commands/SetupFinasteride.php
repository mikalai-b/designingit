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

class SetupFinasteride extends Command
{
    const TYPE_NAME = 'Generic Propecia®';

    const TYPE_DIRECTIONS = 'Take 1 tablet by mouth daily. Take pill at approximately the same time everyday.';
    const TYPE_CONSENT_FORM = '/finasteride-consent-form';
    const TYPE_REQUIRE_AUTO_RENEWAL = 1;
    const TYPE_APPROVED_TEMPLATE = '<p>I am sending the prescription to the pharmacists now, and they will ship you your prescription within 24 hours.</p><p>Finasteride is an FDA-approved prescription medication that prevents further hair loss, and also regrows hair that has previously been lost. While Finasteride starts to work immediately to stop hair loss and regrow hair, it does take 3 months before individuals begin to notice the effects, and continued use prevents further hair loss, and works to regrow hair.</p><p>Finasteride only works when taken on a daily basis.</p><p>Most patients tolerate finasteride very well, and finasteride is an FDA-approved medication. All medications can cause side effects, however, and patients should be aware of possible side effects from using finasteride. In clinical trials, approximately 1.3% of men who used finasteride experienced erectile dysfunction, compared to 0.7% of men who took a non-therapeutic placebo. Decreased libido was reported by 1.8% of men who took finasteride and 1.3% percent of men who were given a non-therapeutic placebo. 0.8% of men who used finasteride in clinical trials reported a lower semen volume. In comparison, 0.4% of men who used a placebo pill reported lower semen volume.</p><p>Finasteride may also cause other, less common side effects. These include depression, pain in the testicles, changes in the breasts (for example, male breast growth, lumps, pain or discharge from the nipples), itching, rash, hives, facial swelling and difficulty breathing or swallowing.</p><p>If you are experiencing any of these side effects you should immediately stop this medication and contact me. If you experience any rash, with facial swelling or difficulty breathing or swallowing, you must seek immediate medical attention, as they can indicate a severe allergic reaction that may be life threatening.</p><p>This medication should not be handled by women who are pregnant or who may become pregnant.</p><p>If you have any questions now or in the future, please do not hesitate to send me a message through your patient portal at CosmeticRx (all information is always HIPAA-encrypted and secure).</p>';
    const TYPE_DEFAULT_REFILLS = null;
    const TYPE_DEFAULT_EXPIRATION = 12;
    const TYPE_DEFAULT_PERIOD = 90;
    const TYPE_AVAILABLE_PERIODS = ['90'];
    const PRODUCT_ID = '2946b802-42d4-459f-8c0d-0e8242af73ed';
    const PRODUCT_NAME = 'Finasteride';
    const PRODUCT_STRENGTH = '1mg';
    const PRODUCT_QUANTITY = '90 ct';
    const PRODUCT_INFO = 'For male pattern hair loss';
    const PRODUCT_PRICE = 39;
    const PRODUCT_NDC_NUMBER = '67877-0455-90';
    const PRODUCT_THUMBNAIL = '/images/thumbnails/finasteride.jpg';

    const QUESTIONS = [
        [
            'content' => 'When did you first notice your hair loss?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'Not started yet, I would like to prevent hair loss',
                    'Within the past 3 months',
                    'Within the past year',
                    'Over 1 year ago',
                ],
                'multi' => 0,
            ],
        ],
        [
            'content' => 'Where did you first notice your hair loss?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'Not started yet, I would like to prevent hair loss',
                    'I have a receding hairline',
                    'I am getting a bald spot on the crown of my head',
                    'Both hairline and crown',
                    'It\'s patchy with some odd hair loss all over my scalp',
                ],
                'multi' => 0,
            ],
        ],
        [
            'content' => 'Have you experienced any of the following?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'Dandruff',
                    'A sudden increase in hair loss',
                    'Losing body hair',
                    'Pain, itching, or burning of the scalp',
                    'Red rings or other rashes on the scalp',
                    'Hair loss other than on your head',
                    'A diagnosis of scalp psoriasis or eczema',
                    'No, I have not experienced any of these symptoms',
                ],
                'multi' => 1,
            ],
        ],
        [
            'content' => 'Have you previously taken finasteride (generic Propecia)?',
            'type' => 'yes-no',
        ],
        [
            'content' => 'Do you have or have you had any of the following conditions?',
            'type' => 'multiple-choice',
            'config' => [
                'options' => [
                    'Kidney disease',
                    'Liver disease',
                    'Thyroid disease',
                    'Cancer',
                    'HIV',
                    'A weakened immune system due to other causes',
                    'Recurrent yeast or fungal infections',
                    'Prostate cancer',
                    'Prostate enlargement (BPH)',
                    'Rheumatological disorders or automminue diseases (such as lupus, discoid lupus, psoriatic arthritis, or sarcoid)',
                    'No, I have not had any of these medical condidtions',
                ],
                'multi' => 1,
            ],
        ],
        [
            'content' => 'Please list in the box all your current medications, vitamins, and/or supplements',
            'type' => 'short-answer',
        ],
        [
            'content' => 'Please list in the box any allergies that you have',
            'type' => 'short-answer',
        ],
        [
            'content' => 'Please list in the box any current or past medical problems and surgeries',
            'type' => 'short-answer',
        ],
        [
            'content' => 'I understand that Finasteride (Propecia) can have two very uncommon, but significant side effects.  These include psychological side effects; depression and anxiety.  Sexual side effects can occur.  Overall, sexual side effects occur in about 4% of patients taking medication versus about 2% taking a placebo (a "sugar pill"). For over 50% of patients, the side effects go away even if the medication is continued, and the side effects nearly always go away if stopped. If you experience any side effect, you will contact your physician and stop the medication.',
            'type' => 'acknowledgement',
            'config' => [
                'yes' => 'Yes, I acknowledge the potential side effects of using finasteride',
                'no' => 'No, I do not acknowledge the potential side effects of using finasteride',
            ],
        ]
    ];

    const SYMPTOM_ID = 'L64.9';
    const SYMPTOM_CONTENT = 'Male pattern hair loss';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:setup-finasteride';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process for setting up Finasteride in the DB.';

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
        $this->addNewPhotoType();
        $this->addNewProductType();
        $this->addNewProduct();
        $this->addNewQuestions();
        $this->setPhotoTypesForNewProduct();
        $this->addSymptoms();
    }

    private function addNewPhotoType()
    {
        $this->info('Adding new photo type...');
        $result = $this->photoTypes->findOneBy([
            'name' => 'Hair Shot',
        ]);
        if (!$result) {
            $photoType = $this->photoTypes->create();
            $photoType->setName('Hair Shot');
            $photoType->setDescription('Please take and submit a photo of your hair - taken from above your head (and looking down at your hair) so that the dermatologists can see the top of your head and hair.');
            $photoType->setMaxUploads(2);
            $photoType->setDisplayOrder(2);
            $this->photoTypes->store($photoType, true);
        }
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
                'Hair Shot',
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
