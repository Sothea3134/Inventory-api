<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ActiveNav
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $active['product']  = '';
        $active['import']   = '';
        $active['dashboard'] = '';
        $active['customer'] = '';
        $active['sales'] = '';
        $active['user-management'] = '';
        $active['reports'] = '';

        $route = $request->getPathInfo();

        switch (true) {
            case (strstr($route, '/reports/product-sales')):
                $active['reports'] = 'active';
                break;
            case (strstr($route, '/reports/product-imports')):
                $active['reports'] = 'active';
                break;
            case (strstr($route, '/reports/filter')):
                $active['reports'] = 'active';
                break;
            case (strstr($route, '/import')):
                $active['import'] = 'active';
                break;
            case (strstr($route, '/product')):
                $active['product'] = 'active';
                break;
            case (strstr($route, '/customer')):
                $active['customer'] = 'active';
                break;
            case (strstr($route, '/sales')):
                $active['sales'] = 'active';
                break;
            case (strstr($route, '/user-management')):
                $active['user-management'] = 'active';
                break;
            default:
                $active['dashboard'] = 'active';
        }

        View::share('active', $active);

        return $next($request);
    }
}
