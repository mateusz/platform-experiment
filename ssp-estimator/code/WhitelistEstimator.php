<?php

namespace SilverStripe\SSP;

use SilverStripe\Core\Extension;
use SilverStripe\Platform\Whitelist;

class WhitelistEstimator extends Extension
{
    public function getPrice(Whitelist $w)
    {
        printf("Whitelist: %d items\n", count($w->getWhitelist()));
        $price = 0.1 * count($w->getWhitelist());
        return [$price, $price];
    }
}
