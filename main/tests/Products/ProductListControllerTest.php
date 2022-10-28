<?php

namespace Tbd\Main\Tests\Products;

use Tbd\Main\FeatureFlags\FeatureFlag;
use Tbd\Main\Products\Product;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\ServerRequest;
use Tbd\Main\Products\ProductRepositoryInterface;
use Tbd\Main\Products\ProductsListController;

class ProductListControllerTest extends TestCase
{
//    public function testControllerReturnsValidResponseWithDetailsDisabled()
//    {
//        if(FeatureFlag::isEnabled('show_product_details_on_list')){
//            $this->markTestSkipped("Flag show_product_details_on_list is enabled");
//        }
//
//        $request = new ServerRequest('GET', 'http://example.com/products/');
//
//        $product1 = new Product(1, 'test', 'description', 100);
//        $product2 = new Product(2, 'test2', 'description2', 200);
//
//        $stub = $this->createMock(ProductRepositoryInterface::class);
//        $stub->method('listProducts')
//            ->willReturn([$product1, $product2]);
//
//        $controller = new ProductsListController($stub);
//
//        $response = $controller($request);
//
//        $this->assertInstanceOf(ResponseInterface::class, $response);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
//
//        $output='[
//    {
//        "id": 1,
//        "name": "test"
//    },
//    {
//        "id": 2,
//        "name": "test2"
//    }
//]';
//        $this->assertEquals($output, (string) trim($response->getBody()));
//    }

    public function testControllerReturnsEmptyResponse()
    {
        $request = new ServerRequest('GET', 'http://example.com/products/');

        $stub = $this->createMock(ProductRepositoryInterface::class);
        $stub->method('listProducts')
            ->willReturn([]);

        $controller = new ProductsListController($stub);

        $response = $controller($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $output='[]';
        $this->assertEquals($output, (string) trim($response->getBody()));
    }

    public function testControllerReturnsValidResponse()
    {
        $request = new ServerRequest('GET', 'http://example.com/products/');

        $product1 = new Product(1, 'test', 'description', 100);
        $product2 = new Product(2, 'test2', 'description2', 200);

        $stub = $this->createMock(ProductRepositoryInterface::class);
        $stub->method('listProducts')
            ->willReturn([$product1, $product2]);

        $controller = new ProductsListController($stub);

        $response = $controller($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $output='[
    {
        "id": 1,
        "name": "test"
    },
    {
        "id": 2,
        "name": "test2"
    }
]';
        $this->assertEquals($output, (string) trim($response->getBody()));
    }
}
