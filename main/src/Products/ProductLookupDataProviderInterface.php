<?php

namespace Tbd\Main\Products;

interface ProductLookupDataProviderInterface
{
    public function getData(Product $product): array;
}
