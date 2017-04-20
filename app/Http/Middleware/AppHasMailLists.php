<?php

namespace App\Http\Middleware;

use Closure;
use App\MailList;

class AppHasMailLists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $list_count = MailList::all()->count();

        if($list_count == 0)
            return redirect()->action('ListController@create');
        return $next($request);
    }
}
