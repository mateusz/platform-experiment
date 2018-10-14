<?php

use SilverStripe\Platform\Accessorizes;
use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier;
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

        return $spec;
    }

}
