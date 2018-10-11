<?php

namespace SilverStripe\Platform;

use ReflectionClass;
use SilverStripe\Control\Controller;
use SilverStripe\Core\ClassInfo;

class Spec extends Controller
{
    private static $allowed_actions = array(
        'get'
    );

    private static $url_handlers = [
        'get//$ComponentClass' => 'get',
    ];

    public function get($request)
    {
        $compClass = $request->param('ComponentClass');

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

            $s = $cn::singleton();
            $spec['components'] = $s->roll();
        }

        echo json_encode($spec);
    }
}
