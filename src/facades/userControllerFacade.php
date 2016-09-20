<?php namespace darrenmerrett\ReactUserFramework\facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Cache\CacheManager
 * @see \Illuminate\Cache\Repository
 */
class userControllerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
		
        return 'darrenmerrett\ReactUserFramework\App\Http\Controllers\User\UserController';
		
    }
	
}
