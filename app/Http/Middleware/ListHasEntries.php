<?php

namespace App\Http\Middleware;

use Closure;
use App\MailList;

class ListHasEntries
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
        try {
            $list = MailList::whereId($request->list)->firstOrFail();

            if( ! $list->hasEntries())
                return redirect()->action('ListController@import', $list->id)->withError('Please import list entries. If you just imported entries, please retry the action as the queue is not handled instantly.');

            return $next($request);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
