<?php

namespace Tbd\Recommendations\Recommendations;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class RecommendationsLookupController
{
    private $repository;

    public function __construct(ImpressionsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $id = $request->getAttribute('id');
        $recommendations = $this->repository->getRecommendations($id);

        return Response::json($recommendations);
    }
}