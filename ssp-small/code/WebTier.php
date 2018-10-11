<?php

namespace SilverStripe\SSP\Small;

use SilverStripe\Control\Director;
use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier as PlatformWebTier;

class WebTier extends PlatformWebTier
{
    public function getMin()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 2;
        } else {
            return 1;
        }
    }

    public function getMax()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 4;
        } else {
            return 1;
        }
    }

    public function getCores()
    {
        return 1;
    }

    public function getMemGB()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 2;
        } else {
            return 1;
        }
    }

    public function getBurstable()
    {
        return true;
    }
}
