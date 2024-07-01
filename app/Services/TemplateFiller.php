<?php

class TemplateFiller
{
    /** @var \Product **/
    protected $product;

    /** @var \LineItem **/
    protected $lineItem;

    /** @var array */
    protected $qualifiers = ['[[', ']]'];

    /**
     * @param array $qualifiers 
     * @return void 
     */
    public function setQualifiers(array $qualifiers)
    {
        $this->qualifiers = $qualifiers;
    }

    /**
     * @param \Product $product 
     * @return void 
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @param \LineItem $lineItem 
     * @return void 
     */
    public function setLineItem($lineItem)
    {
        $this->lineItem = $lineItem;
    }

    /**
     * @return array 
     */
    protected function buildMap()
    {
        $map = [];
        if ($this->product) {
            $map += [
                'product.friendly_default_period' => $this->product->getFriendlyDefaultPeriod(),
                'product.period_count' => spell_out_int(count($this->product->getAvailableDashboardPeriods())),
            ];
        }
        if ($this->lineItem) {
            $map += [
                'line_item.price' => sprintf('$%s', $this->lineItem->getPrice()),
            ];
        }
        return $map;
    }

    public function fill($content = null)
    {
        $map = $this->buildMap();

        if ($content) {
            foreach ($map as $key => $value) {
                $key = sprintf('%s%s%s', $this->qualifiers[0], $key, $this->qualifiers[1]);
                $content = str_replace($key, $value, $content);
            }
        }

        return $content;
    }

}