<?php

namespace SilverStripe\SSP;

use Exception;
use SilverStripe\Platform\Accessory;
use SilverStripe\Platform\Spec;
use SilverStripe\Platform\WebTier;

class Estimate extends Spec
{
    private $estimators = [
        WebTier::class => WebTierEstimator::class,
    ];

    public function index($request)
    {
        $accessories = $this->getAccessories($request);
        $min = 0;
        $max = 0;

        /** @var Accessory $a */
        foreach ($accessories as $a) {
            if (empty($this->estimators[get_class($a)])) {
                throw new Exception(sprintf('Estimator not found for class "%s"', get_class($a)));
            }

            /** @var Estimator $e */
            $eClass = $this->estimators[get_class($a)];
            $e = new $eClass();
            try {
                list($min, $max) = $e->getPrice($a);
            } catch (Exception $e) {
                printf("Could assemble accessories that would suit your needs. Review errors and try again.\n");
                return;
            }
        }

        printf("Platform price estimate: \n");
        printf("US$ %.2f - %.2f\n", ceil($min * 1.5), ceil($max * 1.5));
    }
}
