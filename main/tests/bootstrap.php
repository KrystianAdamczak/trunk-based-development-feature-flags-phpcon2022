<?php

use Tbd\Main\FeatureFlags\EnvOverrider;
use Tbd\Main\FeatureFlags\FeatureFlag;
use Tbd\Main\FeatureFlags\InMemoryFeatureFlags;

require_once __DIR__ . '/../vendor/autoload.php';

$initialFlags = require __DIR__ . '/../src/Flags.php';
$envOverrider = new EnvOverrider();
$featureFlags = new InMemoryFeatureFlags($envOverrider->overrideFlags($initialFlags));
FeatureFlag::setFeatureFlags($featureFlags);
