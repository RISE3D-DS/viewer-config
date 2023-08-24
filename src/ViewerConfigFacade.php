<?php

namespace Rise3d\ViewerConfig;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rise3d\ViewerConfig\Skeleton\SkeletonClass
 */
class ViewerConfigFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'viewer-config';
    }
}
