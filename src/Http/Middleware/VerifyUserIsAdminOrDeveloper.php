<?php

namespace Naust\Impersonator\Http\Middleware;

class VerifyUserIsAdminOrDeveloper
{
    /**
     * Determine if the authenticated user is a developer.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {


        if ($request->user() && $request->user()->hasRole(config('impersonator.roles'))) {
            return $next($request);
        }

        return $request->ajax() || $request->wantsJson()
            ? response('Unauthorized.', 401)
            : redirect()->guest('login');
    }
}
