<?php
namespace Tbd\Main\FeatureFlags;

interface FeatureFlagsInterface
{
    public function setEnabled(string $flag, bool $enabled = true) : void;
    public function isEnabled(string $flag) : bool;
}
