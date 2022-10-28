<?php

namespace Tbd\Main\Products;

use Tbd\Main\FeatureFlags\FeatureFlag;
use Tbd\Main\Recommendations\RecommendationsService;

class ProductLookupDataProviderAbstraction implements ProductLookupDataProviderInterface
{
    private ProductLookupDataProviderInterface $implementation;

    public function __construct(){
        if(FeatureFlag::isEnabled('show_recommendations_on_product_lookup')){

        }else {
            $this->implementation = new ProductLookupStandardDataProvider();
        }
    }

    public function getImplementation(): ProductLookupDataProviderInterface
    {
        return $this->implementation;
    }

    public function getData(Product $product): array
    {
        return $this->getImplementation()->getData($product);
    }
}
