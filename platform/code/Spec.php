<?php

namespace SilverStripe\Platform;

use Exception;
use ReflectionClass;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\ClassInfo;

class Spec extends Controller
{
    const UAT = 'UAT';

    const PRODUCTION = 'Production';

    const TEST = 'Test';

    const UNSPECIFIED = 'Unspecified';

    private static $allowed_actions = [
        'get',
    ];

    public function get($request)
    {
        echo json_encode($this->getAccessories($request));
    }

    /**
     * @return [] of Accessory singletons
     */
    protected function getAccessories($request)
    {
        $env = $this->getEnv($request);

        $needingAccessories = array_merge(
            ClassInfo::implementorsOf(Accessorizes::class),
            ClassInfo::subclassesFor(AccessorizesBase::class)
        );
        $needingAccessories = array_unique($needingAccessories);

        $accessories = [];
        foreach ($needingAccessories as $className) {
            $cReflection = new ReflectionClass($className);
            if (!$cReflection->isInstantiable()) {
                continue;
            }

            /** @var Accessorizes $c */
            $c = $className::singleton();
            $accessories = array_merge($accessories, $c->needsAccessories($env));
        }

        return $accessories;
    }

    private function getEnv(HTTPRequest $request)
    {
        $env = $request->getVar('env');

        if (empty($env)) {
            throw new Exception('Please supply environment via query variable, e.g. "env=Production"');
        }
        if (!in_array($env, [self::UAT, self::PRODUCTION, self::TEST, self::UNSPECIFIED])) {
            throw new Exception(sprintf('Invalid environment: %s', $env));
        }

        return $env;
    }

}
