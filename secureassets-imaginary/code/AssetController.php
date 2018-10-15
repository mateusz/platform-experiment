<?php

use SilverStripe\Control\Controller;
use SilverStripe\Platform\Accessorizes;
use SilverStripe\Platform\URLRule;

/**
 * This is an example module which needs Platform accessories. Accessory requirements
 * from all sources are merged together before being passed to the platform, so all modules have a say.
 */
class AssetController extends Controller implements Accessorizes
{
    public function needsAccessories($env)
    {
        return [
            new URLRule('^/assets/', 'passthrough'),
        ];
    }
}
