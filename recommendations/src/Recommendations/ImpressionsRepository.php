<?php
namespace Tbd\Recommendations\Recommendations;

class ImpressionsRepository implements ImpressionsRepositoryInterface
{
    private static $impressions = [];

    public function getRecommendations(string $id): array
    {
        $result = self::$impressions;
        unset($result[$id]);
        asort($result, SORT_NUMERIC);
        return array_reverse(array_keys($result));
    }

    public function createImpression(string $id, int $impressions): void
    {
        if(isset(self::$impressions[$id])){
            self::$impressions[$id] += $impressions;
        }else{
            self::$impressions[$id] = $impressions;
        }
    }
}