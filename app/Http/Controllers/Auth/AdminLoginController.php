<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Admin\Admin;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var string
     */
    protected string $redirectTo = '/admin2';

    /**
     * @return Factory|Application|View|RedirectResponse
     */
    public function showLoginPage(): Factory|Application|View|RedirectResponse
    {
        if (isset(request()->all()['token'])) {
            $token = request()->all()['token'];
            if (isset(request()->all()['email'])) {
                $email = request()->all()['email'];
                $admin = Admin::where('email', $email)->where('remember_token', $token)->first();
                if ($admin) {
                    Auth::guard('admin_web')->login($admin);

                    return redirect()->route('admin-dashboard');
                }
            }
        }

        return view('new_admin.auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin_web')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
