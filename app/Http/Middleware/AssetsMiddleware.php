<?php namespace App\Http\Middleware;

use App\Libraries\Assets\Collection;
use App\Libraries\Assets\Orchestrator;
use Closure;

class AssetsMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\App::environment() === 'production') {
            return $next($request);
        }

        /** @var Orchestrator $ocherstator */
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');

        foreach (config('assets.groups') as $groupname => $assets) {
            $collection = \App\Libraries\Assets\Collection::createByGroup($groupname);

            try {
                $ocherstator->build($collection, array_diff(Collection::$types, Collection::$staticType));
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        return $next($request);
    }

}
