<?php

namespace App\Http\Middleware;

use Closure;
use App\MailList;

class ListActive
{
    /**
     * Determine if the list is active
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $list = MailList::whereId($request->list)->firstOrFail();

        if($list->isActive())
            return redirect()->action('ListController@index')->withError('You cannot do that to an active mail list');

        return $next($request);
    }
}
