<?php

namespace Tbd\Main\Tests\Products;

use Tbd\Main\FeatureFlags\FeatureFlag;
use Tbd\Main\Products\Product;
use Tbd\Main\Products\ProductImpressionMiddleware;
use Tbd\Main\Products\ProductLookupController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\ServerRequest;
use Tbd\Main\Products\ProductRepositoryInterface;
use Tbd\Main\Recommendations\RecommendationsServiceInterface;

class ProductImpressionMiddlewareTest extends TestCase
{
    public function testControllerReturnsValidResponseWithRecommendationsDisabled()
    {
        if(!FeatureFlag::isEnabled('create_impression_on_product_lookup')){
            $this->markTestSkipped("Flag create_impression_on_product_lookup is disabled");
        }

        if(FeatureFlag::isEnabled('show_recommendations_on_product_lookup')){
            $this->markTestSkipped("Flag show_recommendations_on_product_lookup is enabled");
        }

        $request = new ServerRequest('GET', 'http://example.com/products/3');
        $request = $request->withAttribute("id", "3");

        $product = new Product(3, 'test', 'description', 100);

        $stub = $this->createMock(ProductRepositoryInterface::class);
        $stub->method('findProduct')
            ->will($this->returnValueMap([["3", $product]]));

        $controller = new ProductLookupController($stub);

        $recoStub = $this->createMock(RecommendationsServiceInterface::class);
        $recoStub->method('createImpression')
            ->will($this->returnValueMap([[3, true]]));
        $recoStub->expects($this->once())
            ->method('createImpression');

        $middleware = new ProductImpressionMiddleware($recoStub);

        $response = $middleware($request, $controller);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $output='{
    "name": "test",
    "description": "description",
    "price": 100.0
}';
        $this->assertEquals($output, (string) trim($response->getBody()));
    }

    public function testControllerReturnsValidResponseWithRecommendationsEnabled()
    {
        if(!FeatureFlag::isEnabled('create_impression_on_product_lookup')){
            $this->markTestSkipped("Flag create_impression_on_product_lookup is disabled");
        }

        if(!FeatureFlag::isEnabled('show_recommendations_on_product_lookup')){
            $this->markTestSkipped("Flag show_recommendations_on_product_lookup is disabled");
        }

        $request = new ServerRequest('GET', 'http://example.com/products/3');
        $request = $request->withAttribute("id", "3");

        $product = new Product(3, 'test', 'description', 100);

        $stub = $this->createMock(ProductRepositoryInterface::class);
        $stub->method('findProduct')
            ->will($this->returnValueMap([["3", $product]]));

        $controller = new ProductLookupController($stub);

        $recoStub = $this->createMock(RecommendationsServiceInterface::class);
        $recoStub->method('getRecommendations')
            ->will($this->returnValueMap([[3, [1]]]));
        $recoStub->method('createImpression')
            ->will($this->returnValueMap([[3, true]]));
        $recoStub->expects($this->once())
            ->method('createImpression');

        $controller->getDataProvider()->getImplementation()->setService($recoStub);

        $middleware = new ProductImpressionMiddleware($recoStub);

        $response = $middleware($request, $controller);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $output='{
    "name": "test",
    "description": "description",
    "price": 100.0,
    "recommendations": [
        1
    ]
}';
        $this->assertEquals($output, (string) trim($response->getBody()));
    }

    public function testControllerReturns404Response()
    {
        if(!FeatureFlag::isEnabled('create_impression_on_product_lookup')){
            $this->markTestSkipped("Flag create_impression_on_product_lookup is disabled");
        }

        $request = new ServerRequest('GET', 'http://example.com/products/3');
        $request = $request->withAttribute("id", "3");

        $stub = $this->createMock(ProductRepositoryInterface::class);
        $stub->method('findProduct')
            ->will($this->returnValueMap([["3", null]]));

        $recoStub = $this->createMock(RecommendationsServiceInterface::class);
        $recoStub->method('createImpression')
            ->will($this->returnValueMap([[3, true]]));
        $recoStub->expects($this->never())
            ->method('createImpression');

        $controller = new ProductLookupController($stub);
        $middleware = new ProductImpressionMiddleware($recoStub);

        $response = $middleware($request, $controller);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('text/plain; charset=utf-8', $response->getHeaderLine('Content-Type'));

        $output='Product not found';
        $this->assertEquals($output, (string) trim($response->getBody()));
    }
}
