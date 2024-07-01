<?php

use Illuminate\Http\Resources\Json\Resource;

class CartResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productRepository = app()->make(Products::class);
        if ($request->input('couponCode')) {
            $this->getCheckout()->setCouponCode($request->input('couponCode'));
        }
        return [
            'items' => collect($this->content())
                ->map(function($item) use ($productRepository, $request) {
                    $product = $productRepository->find($item->id);
                    return [
                        'id' => $item->id,
                        'rowId' => $item->rowId,
                        'name' => $item->name,
                        'qty' => $item->qty,
                        'price' => $this->getPriceForProduct($product),
                        'firstShipmentPrice' => $this->getFirstShipmentPriceForProduct($product),
                        'options' => $item->options,
                        'subtotal' => $item->subtotal,
                        'product' => (new ProductResource($product))->toArray($request),
                    ];
                })->values()
        ];
    }
}
