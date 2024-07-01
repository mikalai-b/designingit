<?php

use Illuminate\Database\Seeder;

class PhotosSeeder extends Seeder
{
    static protected $types = [
        'Face Shot' => [
            'display_order' => 1,
            'max_uploads'   => 3,
            'description'   => "Please submit a close-up photo of your face, head on, for your dermatologist to review.",
            'products'      => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ],
        'Eyelash Close-ups' => [
            'display_order' => 2,
            'max_uploads'   => 3,
            'description'   => "Please submit a close-up pictures of your eyelashes, for your dermatologist to review.",
            'products'      => ['Bimatoprost Ophthalmic Solution']
        ],
        'Proof of Address' => [
            'display_order' => 3,
            'max_uploads'   => 1,
            'description'   => "To process your order, we’ll need proof of your address. Please upload a picture of a government issued photo ID (ex. driver’s license or passport).",
            'products'      => ['Tretinoin cream', 'Bimatoprost Ophthalmic Solution']
        ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(PhotoTypes $types, ProductTypes $product_types, Products $products)
    {
        foreach (static::$types as $type_name => $details) {
            $type = $types->findOneByName($type_name);

            if (!$type) {
                $type = $types->create();
                $type->setName($type_name);
            }

            $type->setDescription($details['description']);
            $type->setDisplayOrder($details['display_order']);
            $type->setMaxUploads($details['max_uploads']);
            $types->store($type, TRUE);


            foreach ($products->findAll() as $product) {
                if ($product->getPhotoTypes()->contains($type)) {
                    if (!in_array($product->getType()->getName(), $details['products'])) {
                        $product->getPhotoTypes()->removeElement($type);
                    }

                } else {
                    if (in_array($product->getType()->getName(), $details['products'])) {
                        $product->getPhotoTypes()->add($type);
                    }

                }

                $products->store($product, TRUE);
            }
        }
    }
}
