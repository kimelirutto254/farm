<?php

namespace App\Http\Middleware;


use App\Models\Utility;
use Closure;
use Illuminate\Http\Request;

class XSS
{

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
        if (\Auth::check()) {
            \App::setLocale(\Auth::user()->lang);
            if (\Auth::user()->type == 'super admin') {
                $migrations             = $this->getMigrations();
                $dbMigrations           = $this->getExecutedMigrations();
                $Modulemigrations = glob(base_path().'/Modules/LandingPage/Database'.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR.'*.php');
                $numberOfUpdatesPending = (count($migrations) + count($Modulemigrations)) - count($dbMigrations);

                if ($numberOfUpdatesPending > 0) {
                    return redirect()->route('LaravelUpdater::welcome');
                }
            }
        }

        $input = $request->all();
        array_walk_recursive(
            $input,
            function (&$input) {
                $input = strip_tags($input);
            }
        );
        return $next($request);
    }
}
