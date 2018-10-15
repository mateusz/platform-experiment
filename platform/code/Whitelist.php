<?php

namespace SilverStripe\Platform;

class Whitelist extends Accessory
{
    /**
     * @var [] of CIDRs
     */
    private $whitelist = [];

    public function addCIDRs($cidrs)
    {
        $this->whitelist = array_merge($this->whitelist, $cidrs);
    }

    public function getWhitelist()
    {
        return $this->whitelist;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'whitelist' => $this->getWhitelist(),
        ]);
    }
}
