<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * @deprecated Use EnsureAdmin middleware instead.
 */
class IsAdmin extends EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        return parent::handle($request, $next);
    }
}
