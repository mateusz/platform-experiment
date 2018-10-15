<?php

use SilverStripe\Platform\Accessorizes;
use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier;
use SilverStripe\Platform\Whitelist;
use SilverStripe\SSP\Small;
use SilverStripe\SSP\SMAZ;

class Platform extends Small implements Accessorizes
{
    public function needsAccessories($env)
    {
        $spec = parent::needsAccessories($env);

        if ($env===Spec::PRODUCTION) {
            $spec = [
                new WebTier(600, 8000, 6144, true)
            ];
        }

        $wh = new Whitelist();
        $wh->addCIDRs(['1.2.3.4/31', '2.3.4.0/24']);
        $spec[] = $wh;

        return $spec;
    }

}
