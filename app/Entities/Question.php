<?php

/**
 *
 */
class Question extends Base\Question
{
    /**
     * Instantiate a new Question
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }

    /**
     * 
     */
    public function addProduct(Product $product)
    {
        if ($this->products->contains($product)) {
            return;
        }

        $this->products->add($product);
        $product->addQuestion($this);
    }

    /**
     * 
     */
    public function getFilledContent(Product $product)
    {
        $templateFiller = app()->make(TemplateFiller::class);
        $templateFiller->setProduct($product);
        return $templateFiller->fill($this->getContent());
    }
}
