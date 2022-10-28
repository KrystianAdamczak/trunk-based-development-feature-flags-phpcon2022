<?php

namespace Tbd\Main\Products;

class ProductLookupStandardDataProvider implements ProductLookupDataProviderInterface
{
    public function getData(Product $product): array
    {
        $data = [
            "name" => $product->title,
            "description" => $product->description,
            "price" => $product->price,
        ];
        return $data;
    }
}
