<?php

namespace App\Http\Middleware;

use Closure;
use App\MailQueue;

class HasQueueEntries
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

        $queue_count = MailQueue::count();

        if($queue_count == 0)
            return redirect()->back()->withError('No queue entries exist');

        return $next($request);
    }
}
