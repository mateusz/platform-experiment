<?php

namespace SilverStripe\SSP;

use SilverStripe\Platform\Accessory;

abstract class Estimator
{
    public abstract function getPrice(Accessory $a);
}
