<?php

namespace Tbd\Recommendations\Recommendations;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class ProductImpressionCreateController
{
    private $repository;

    public function __construct(ImpressionsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $post = $request->getParsedBody();
        if(isset($post['impressions'])){
            $impressions = (int)$post['impressions'];
        }else{
            $impressions = 1;
        }

        $this->repository->createImpression($id, $impressions);

        return Response::plaintext("OK");
    }
}