<?php
namespace Tbd\Main\FeatureFlags;

class InMemoryFeatureFlags implements FeatureFlagsInterface
{
    private $flags = [];

    public function __construct(array $flags = []){
        $this->flags = $flags;
    }

    public function setEnabled(string $flag, bool $enabled = true) : void
    {
        $this->flags[$flag] = $enabled;
    }

    public function isEnabled(string $flag) : bool
    {
        return isset($this->flags[$flag]) ? $this->flags[$flag] : false;
    }
}
