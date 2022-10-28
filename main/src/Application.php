<?php

namespace Tbd\Main;

use FrameworkX\App;
use FrameworkX\Container;
use React\Http\Message\Response;
use Tbd\Main\FeatureFlags\FeatureFlag;
use Tbd\Main\Products\ProductRepository;
use Tbd\Main\Recommendations\RecommendationsService;

class Application
{
    protected App $app;

    public function __construct()
    {
        $diArray = [
            Products\ProductsListController::class => function (ProductRepository $repository) {
                return new Products\ProductsListController($repository);
            },
            Products\ProductLookupController::class => function (ProductRepository $repository) {
                return new Products\ProductLookupController($repository);
            }
        ];

        if(FeatureFlag::isEnabled('create_impression_on_product_lookup')) {
            $diArray[RecommendationsService::class] = function(){
                $address = getenv('RECOMMENDATIONS_SERVICE_URL');
                return new RecommendationsService($address);
            };
            $diArray[Products\ProductImpressionMiddleware::class] = function (RecommendationsService $service) {
                return new Products\ProductImpressionMiddleware($service);
            };
        }

        $container = new Container($diArray);

        $this->app = new App($container);
        $this->app->get('/products',  Products\ProductsListController::class);

        if(FeatureFlag::isEnabled('create_impression_on_product_lookup')) {
            $this->app->get('/products/{id}', Products\ProductImpressionMiddleware::class, Products\ProductLookupController::class);
        }else{
            $this->app->get('/products/{id}', Products\ProductLookupController::class);
        }

        $this->app->get('/', function () {
            return Response::plaintext(
                "Hello trunk!\n"
            );
        });
    }

    public function run(){
        $this->app->run();
    }

}
