<?php

namespace SilverStripe\SSP;

use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier;

class SmallWebTier extends WebTier
{
    public function getGuaranteedCores()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 0.8;
        } else {
            return 0.1;
        }
    }

    public function getHighlyAvailable()
    {
        return true;
    }

    public function getBurstCores()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 4;
        } else {
            return 1;
        }
    }

    public function getGuaranteedMemGB()
    {
        if ($this->getEnv()===Spec::PRODUCTION) {
            return 2;
        } else {
            return 1;
        }
    }
}
