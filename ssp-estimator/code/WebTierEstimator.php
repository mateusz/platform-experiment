<?php

namespace SilverStripe\SSP;

use Exception;
use SilverStripe\Core\Extension;
use SilverStripe\Platform\WebTier;

class WebTierEstimator extends Extension
{
    public function getPrice(WebTier $wt)
    {
        $memGBPerAZ = $wt->getGuaranteedMemMB();
        $t2MicroMin = (int)ceil($memGBPerAZ / 1024);
        $t2SmallMin = (int)ceil($memGBPerAZ / 2048);
        $t2MediumMin = (int)ceil($memGBPerAZ / 4096);

        $gcPerAZ = $wt->getGuaranteedCpu();
        $gct2MicroMin = (int)ceil($gcPerAZ / 100);
        $gct2SmallMin = (int)ceil($gcPerAZ / 200);
        $gct2MediumMin = (int)ceil($gcPerAZ / 400);

        if ($gct2MicroMin > $t2MicroMin) {
            $t2MicroMin = $gct2MicroMin;
        }
        if ($gct2SmallMin > $t2SmallMin) {
            $t2SmallMin = $gct2SmallMin;
        }
        if ($gct2MediumMin > $t2MediumMin) {
            $t2MediumMin = $gct2MediumMin;
        }

        if ($wt->isHighlyAvailable()) {
            if ($t2MicroMin === 1) {
                $t2MicroMin = 2;
            }
            if ($t2SmallMin === 1) {
                $t2SmallMin = 2;
            }
            if ($t2MediumMin === 1) {
                $t2MediumMin = 2;
            }
        }

        $bcPerAZ = $wt->getBurstCpu();
        $t2MicroMax = (int)ceil($bcPerAZ / 1000);
        $t2SmallMax = (int)ceil($bcPerAZ / 1000);
        $t2MediumMax = (int)ceil($bcPerAZ / 2000);

        if ($t2MicroMax < $t2MicroMin) {
            $t2MicroMax = $t2MicroMin;
        }
        if ($t2SmallMax < $t2SmallMin) {
            $t2SmallMax = $t2SmallMin;
        }
        if ($t2MediumMax < $t2MediumMin) {
            $t2MediumMax = $t2MediumMin;
        }

        $t2MicroMemDiff = ($t2MicroMin * 1024) - $wt->getGuaranteedMemMB();
        $t2SmallMemDiff = ($t2SmallMin * 2048) - $wt->getGuaranteedMemMB();
        $t2MediumMemDiff = ($t2MediumMin * 4096) - $wt->getGuaranteedMemMB();

        $t2MicroCpuDiff =  ($t2MicroMin * 100) - $wt->getGuaranteedCpu();
        $t2SmallCpuDiff = ($t2SmallMin * 200) - $wt->getGuaranteedCpu();
        $t2MediumCpuDiff = ($t2MediumMin * 400) - $wt->getGuaranteedCpu();

        $t2MicroBurstDiff =  ($t2MicroMax * 1000) - $wt->getBurstCpu();
        $t2SmallBurstDiff = ($t2SmallMax * 1000) - $wt->getBurstCpu();
        $t2MediumBurstDiff = ($t2MediumMax * 2000) - $wt->getBurstCpu();

        if ($t2MediumMemDiff===0 && $t2MediumCpuDiff===0 && $t2MediumBurstDiff===0) {
            $b = 0.0464 * 24 * 31;
            printf("WebTier: Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.medium %d/%d)\n", $wt->getGuaranteedCpu() + $t2MediumCpuDiff, $wt->getBurstCpu() + $t2MediumBurstDiff, $wt->getGuaranteedMemMB() + $t2MediumMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2MediumMin, $t2MediumMax);
            return [$b*$t2MediumMin, $b*$t2MediumMax];
        } else if ($t2SmallMemDiff===0 && $t2SmallCpuDiff===0 && $t2SmallBurstDiff===0) {
            $b = 0.0232 * 24 * 31;
            printf("WebTier: Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.small %d/%d)\n", $wt->getGuaranteedCpu() + $t2SmallCpuDiff, $wt->getBurstCpu() + $t2SmallBurstDiff, $wt->getGuaranteedMemMB() + $t2SmallMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2SmallMin, $t2SmallMax);
            return [$b * $t2SmallMin, $b * $t2SmallMax];
        } else if ($t2MicroMemDiff===0 && $t2MicroCpuDiff===0 && $t2MicroBurstDiff===0) {
            $b = 0.0116 * 24 * 31;
            printf("Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.micro %d/%d)\n", $wt->getGuaranteedCpu() + $t2MicroCpuDiff, $wt->getBurstCpu() + $t2MicroBurstDiff, $wt->getGuaranteedMemMB() + $t2MicroMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2MicroMin, $t2MicroMax);
            return [$b*$t2MicroMin, $b*$t2MicroMax];
        } else {
            printf("WebTier: apologies, but we cannot set our infrastructure up like that. Here are some suggestions:\n");
            printf("  #1) Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.micro %d/%d)\n", $wt->getGuaranteedCpu() + $t2MicroCpuDiff, $wt->getBurstCpu() + $t2MicroBurstDiff, $wt->getGuaranteedMemMB() + $t2MicroMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2MicroMin, $t2MicroMax);
            printf("  #2) Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.small %d/%d)\n", $wt->getGuaranteedCpu() + $t2SmallCpuDiff, $wt->getBurstCpu() + $t2SmallBurstDiff, $wt->getGuaranteedMemMB() + $t2SmallMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2SmallMin, $t2SmallMax);
            printf("  #3) Guaranteed CPU: %d millicores, burst CPU: %d millicores, guaranteed memory: %d MB, HA: %s (t2.medium %d/%d)\n", $wt->getGuaranteedCpu() + $t2MediumCpuDiff, $wt->getBurstCpu() + $t2MediumBurstDiff, $wt->getGuaranteedMemMB() + $t2MediumMemDiff, $wt->isHighlyAvailable() ? 'yes' : 'no', $t2MediumMin, $t2MediumMax);
            throw new Exception('None fits');
        }

    }
}
