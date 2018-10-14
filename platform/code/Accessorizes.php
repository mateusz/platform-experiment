<?php

namespace SilverStripe\Platform;

interface Accessorizes
{
    /**
     * @param string $env
     * @return [] of Accessory
     */
    public function needsAccessories($env);

    /**
     * Do "use Injectable" to provide this.
     *
     * @param string $class Optional classname to create, if the called class should not be used
     * @return static The singleton instance
     */
    public static function singleton($class = null);
}
