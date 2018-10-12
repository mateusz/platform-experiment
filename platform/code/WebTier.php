<?php

namespace SilverStripe\Platform;

abstract class WebTier extends Component
{
    public abstract function getGuaranteedCores();
    public abstract function getBurstCores();
    public abstract function getGuaranteedMemGB();
    public abstract function getHighlyAvailable();

    public function getCategory()
    {
        return 'WebTier';
    }

    public function roll()
    {
        return array_merge(parent::roll(), [
            'guaranteed_cores' => $this->getGuaranteedCores(),
            'burst_cores' => $this->getBurstCores(),
            'guaranteed_mem_gb' => $this->getGuaranteedMemGB(),
            'highly_available' => $this->getHighlyAvailable(),
        ]);
    }
}
