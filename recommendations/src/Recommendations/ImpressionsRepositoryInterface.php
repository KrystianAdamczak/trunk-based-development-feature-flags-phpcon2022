<?php
namespace Tbd\Recommendations\Recommendations;

interface ImpressionsRepositoryInterface
{
    /**
     * @return string[]
     */
    public function getRecommendations(string $id): array;

    public function createImpression(string $id, int $impressions): void;
}