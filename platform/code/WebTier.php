<?php

namespace SilverStripe\Platform;

abstract class WebTier extends Component
{
    public abstract function getMax();
    public abstract function getMin();
    public abstract function getCores();
    public abstract function getMemGB();
    public abstract function getBurstable();

    public function getCategory()
    {
        return 'WebTier';
    }

    public function roll()
    {
        return array_merge(parent::roll(), [
            'min' => $this->getMin(),
            'max' => $this->getMax(),
            'cores' => $this->getCores(),
            'mem' => $this->getMemGB(),
            'burstable' => $this->getBurstable(),
        ]);
    }
}
