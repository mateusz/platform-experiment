<?php

namespace SilverStripe\Platform;

use Exception;
use ReflectionClass;
use SilverStripe\Control\Controller;
use SilverStripe\Core\ClassInfo;

class Spec extends Controller
{
    const UAT = 'UAT';

    const PRODUCTION = 'Production';

    const TEST = 'Test';

    const UNSPECIFIED = 'Unspecified';

    private static $allowed_actions = array(
        'get'
    );

    private static $url_handlers = [
        'get//$ComponentClass' => 'get',
    ];

    public function get($request)
    {
        $compClass = $request->param('ComponentClass');
        $env = $request->getVar('env');

        if (empty($env)) {
            throw new Exception('Please supply environment via query variable, e.g. "env=Production"');
        }
        if (!in_array($env, [self::UAT, self::PRODUCTION, self::TEST, self::UNSPECIFIED])) {
            throw new Exception(sprintf('Invalid environment: %s', $env));
        }

        if (!$compClass) {
            $compClass = Component::class;
        }

        $classNames = array_values(ClassInfo::subclassesFor($compClass));
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
}
