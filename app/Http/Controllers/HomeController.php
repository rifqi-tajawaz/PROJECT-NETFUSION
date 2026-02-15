<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the home request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return Auth::check()
            ? redirect()->route('mikrotik-suite.dashboard')
            : redirect()->route('login');
    }

    /**
     * Handle the root/catch-all request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function root()
    {
        // Redirect any unmatched route to the index logic, which handles auth/guest redirection.
        return $this->index();
    }
}
