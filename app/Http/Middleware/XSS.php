<?php

namespace App\Http\Middleware;

use Cookie;
use App\Models\LandingPageSections;
use App\Models\Utility;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            App::setLocale(Auth::user()->lang);

            if (Auth::user()->type == 'super admin') {
                $migrations             = $this->getMigrations();
                $dbMigrations           = $this->getExecutedMigrations();
                $numberOfUpdatesPending = count($migrations) - count($dbMigrations);

                if ($numberOfUpdatesPending > 0) {
                    return redirect()->route('LaravelUpdater::welcome');
                }
            }
        }

        $input = $request->all();
        // array_walk_recursive(
        //     $input,
        //     function (&$input) {
        //         $input = strip_tags($input);
        //     }
        // );
        $request->merge($input);

        return $next($request);
    }
}
