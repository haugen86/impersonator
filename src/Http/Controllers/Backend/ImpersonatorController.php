<?php

namespace Naust\Impersonator\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonatorController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('dev')->except('stopImpersonating');
    }

    /**
     * Impersonate the given user.
     *
     * @param Request $request
     * @param string  $userId
     *
     * @return Response
     */
    public function impersonate(Request $request, $userId)
    {
        $request->session()->flush();

        // We will store the original user's ID in the session so we can remember who we
        // actually are when we need to stop impersonating the other user, which will
        // allow us to pull the original user back out of the database when needed.
        $request->session()->put(
            'naust:impersonator', $request->user()->id
        );

        Auth::login(User::findOrFail($userId));

        return redirect('/');
    }

    /**
     * Stop impersonating and switch back to primary account.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function stopImpersonating(Request $request)
    {
        $currentId = Auth::id();

        // We will make sure we have an impersonator's user ID in the session and if the
        // value doesn't exist in the session we will log this user out of the system
        // since they aren't really impersonating anyone and manually hit this URL.
        if (!$request->session()->has('naust:impersonator')) {
            Auth::logout();

            return redirect('/');
        }

        $userId = $request->session()->pull(
            'naust:impersonator'
        );

        // After removing the impersonator user's ID from the session so we can retrieve
        // the original user. Then, we will flush the entire session to clear out any
        // stale data from while we were doing the impersonation of the other user.
        $request->session()->flush();

        Auth::login(User::findOrFail($userId));

        return redirect('/');
    }
}
