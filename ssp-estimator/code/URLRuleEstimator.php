<?php

namespace SilverStripe\SSP;

use SilverStripe\Core\Extension;
use SilverStripe\Platform\URLRule;

class URLRuleEstimator extends Extension
{
    public function getPrice(URLRule $r)
    {
        printf("URLRule: %s\n", $r->getPath());
        return [0.1, 0.1];
    }
}
