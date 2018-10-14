<?php

namespace SilverStripe\Platform;

use JsonSerializable;

abstract class Accessory implements JsonSerializable
{
    protected $env;

    public function setEnv($env)
    {
        $this->env = $env;
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getName()
    {
        return str_replace('\\', '_', get_class($this));
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
        ];
    }
}
