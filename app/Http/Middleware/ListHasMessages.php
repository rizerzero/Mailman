<?php

namespace App\Http\Middleware;

use Closure;
use App\MailList;

class ListHasMessages
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


            if(! $list->hasMessages())
                return redirect()->action('MessageController@create', $list->id)->withError('Please create a message first');

            return $next($request);

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }




    }
}
