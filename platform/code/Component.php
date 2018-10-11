<?php

namespace SilverStripe\Platform;

use SilverStripe\Core\Injector\Injectable;

abstract class Component
{
    use Injectable;

    public abstract function getCategory();

    public function getName()
    {
        return str_replace('\\', '_', get_class($this));
    }

    public function roll()
    {
        return [
            'category' => $this->getCategory(),
            'name' => $this->getName(),
        ];
    }
}
