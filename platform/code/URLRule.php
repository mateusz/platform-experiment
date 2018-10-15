<?php

namespace SilverStripe\Platform;

class URLRule extends Accessory
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $behaviour;

    public function __construct($path, $behaviour)
    {
        $this->path = $path;
        $this->behaviour = $behaviour;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getBehaviour()
    {
        return $this->behaviour;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'path' => $this->getPath(),
            'behaviour' => $this->getBehaviour(),
        ]);
    }
}
