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

    private static $allowed_actions = array(
        'get',
        'price',
    );

    private static $url_handlers = [
        'get//$ComponentClass' => 'get',
        'price' => 'price',
    ];

    public function price($request)
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
            $price += $s->price();
        }

        echo $price;
    }

    public function get($request)
    {
        $compClass = $request->param('ComponentClass');
        $env = $this->getEnv($request);

        if (!$compClass) {
            $compClass = Component::class;
        }

        $platformClass = sprintf('SilverStripe\Platform\%s', $compClass);
        $classNames = ClassInfo::subclassesFor($platformClass);
        $spec = [
            'components' => [],
        ];
        foreach ($classNames as $cn) {
            $rc = new ReflectionClass($cn);
            if (!$rc->isInstantiable()) {
                continue;
            }

            /** @var Component $s */
            $s = $cn::singleton();
            $s->setEnv($env);
            $spec['components'] = $s->roll();
        }

        echo json_encode($spec);
    }

    protected function getEnv(HTTPRequest $request)
    {
        $env = $request->getVar('env');

        if (empty($env)) {
            throw new Exception('Please supply environment via query variable, e.g. "env=Production"');
        }
        if (!in_array($env, [self::UAT, self::PRODUCTION, self::TEST, self::UNSPECIFIED])) {
            throw new Exception(sprintf('Invalid environment: %s', $env));
        }
    }
}
