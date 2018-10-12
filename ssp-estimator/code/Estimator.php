<?php

namespace SilverStripe\Platform;

use Exception;
use ReflectionClass;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\ClassInfo;

class Estimator extends Spec
{
    private static $allowed_actions = array(
        'estimate',
    );

    public function estimate($request)
    {
        $env = $this->getEnv($request);
        $price = 0;

        $classNames = ClassInfo::subclassesFor(Component::class);
        foreach ($classNames as $cn) {
            $rc = new ReflectionClass($cn);
            if (!$rc->isInstantiable()) {
                continue;
            }

            /** @var Component $s */
            $s = $cn::singleton();
            $s->setEnv($env);
            $price += $s->getPrice();
        }

        echo $price;
    }
}
