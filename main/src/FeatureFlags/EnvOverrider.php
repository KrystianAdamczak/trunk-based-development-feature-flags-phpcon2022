<?php
namespace Tbd\Main\FeatureFlags;

class EnvOverrider
{
    public function overrideFlags(array $flags) : array
    {
        foreach($flags as $flag => $value){
            $env = getenv("FEATURE_FLAG_".strtoupper($flag));
            if($env !== false){
                $flags[$flag] = (bool)$env;
            }
        }
        return $flags;
    }
}
