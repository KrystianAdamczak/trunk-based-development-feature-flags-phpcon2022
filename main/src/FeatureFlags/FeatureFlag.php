<?php

namespace Tbd\Main\FeatureFlags;

class FeatureFlag
{
    private static FeatureFlagsInterface $instance;

    public static function setFeatureFlags(FeatureFlagsInterface $featureFlags) : void
    {
        self::$instance = $featureFlags;
    }

    public static function isEnabled(string $flag) : bool
    {
        if(isset(self::$instance)) {
            return self::$instance->isEnabled($flag);
        }
        return false;
    }
}
