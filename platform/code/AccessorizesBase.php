<?php

namespace SilverStripe\Platform;
use SilverStripe\Core\Injector\Injectable;

/**
 * There is an implied hack in there to let us find all subclasses of "Accessorizes" implementors via manifest.
 * SilverStripe does not return subclasses of implementors when calling ClassInfo::implementorsOf.
 */
abstract class AccessorizesBase implements Accessorizes
{
    use Injectable;
}
