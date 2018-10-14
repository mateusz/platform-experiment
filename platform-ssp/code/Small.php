<?php

namespace SilverStripe\SSP;

use SilverStripe\Platform\AccessorizesBase;
use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier;

abstract class Small extends AccessorizesBase
{
    public function needsAccessories($env)
    {
        if ($env===Spec::PRODUCTION) {
            return [
                new WebTier(800, 8000, 8192, true)
            ];
        } else if ($env===Spec::UAT) {
            return [
                new WebTier(100, 1000, 1024, false)
            ];
        } else {
            return [
                new WebTier(200, 4000, 2048, true)
            ];
        }
    }
}
