<?php

namespace SilverStripe\Platform;

use Exception;

class WebTier extends Accessory
{
    /**
     * @var int
     */
    private $guaranteedCpu;

    /**
     * @var int
     */
    private $burstCpu;

    /**
     * @var int
     */
    private $guaranteedMemMB;

    /**
     * @var bool
     */
    private $highlyAvailable;

    public function __construct($guaranteedCpu, $burstCpu, $guaranteedMemMB, $highlyAvailable)
    {
        if ($guaranteedCpu<100) {
            throw new Exception(sprintf('Value of guaranteedCpu is suspiciously low ("%d"), needs to be at least "100" '
                . '- guaranteedCpu is expressed in millicores, e.g. 1 core = 1000 millicores', $guaranteedCpu));
        }
        if ($burstCpu<100) {
            throw new Exception(sprintf('Value of burstCpu is suspiciously low ("%d"), needs to be at least "100" '
                . '- burstCpu is expressed in millicores, e.g. 1 core = 1000 millicores', $burstCpu));
        }
        if ($guaranteedMemMB<100) {
            throw new Exception(sprintf('Value of guaranteedMemMB is suspiciously low ("%d"), needs to be at least '
                . '"100" - guaranteedMemMB is expressed in megabytes.', $guaranteedMemMB));
        }

        $this->guaranteedCpu = $guaranteedCpu;
        $this->burstCpu = $burstCpu;
        $this->guaranteedMemMB = $guaranteedMemMB;
        $this->highlyAvailable = $highlyAvailable;
    }

    public function getGuaranteedCpu()
    {
        return $this->guaranteedCpu;
    }

    /**
     * @return int
     */
    public function getBurstCpu()
    {
        return $this->burstCpu;
    }

    /**
     * @return int
     */
    public function getGuaranteedMemMB()
    {
        return $this->guaranteedMemMB;
    }

    /**
     * @return bool
     */
    public function isHighlyAvailable()
    {
        return $this->highlyAvailable;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'guaranteed_cpu' => $this->getGuaranteedCpu(),
            'burst_cpu' => $this->getBurstCpu(),
            'guaranteed_mem_gb' => $this->getGuaranteedMemMB(),
            'highly_available' => $this->isHighlyAvailable(),
        ]);
    }
}
