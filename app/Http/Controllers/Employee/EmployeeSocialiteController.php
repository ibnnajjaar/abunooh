<?php

namespace App\Http\Controllers\Employee;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class EmployeeSocialiteController
{
    public function redirect(string $provider)
    {
        if ($provider === 'google') {
            try {
                return Socialite::driver($provider)
                                ->redirectUrl(config('services.google.employee_redirect'))
                                ->redirect();
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }

        return to_route('filament.employee.auth.login')->withErrors(['provider' => 'Provider not supported.']);
    }

    public function callback(Request $request, string $provider)
    {
        try {
            $socialite_user = Socialite::driver($provider)
                                       ->redirectUrl(config('services.google.employee_redirect'))
                                       ->user();
        } catch (\Exception $e) {
            return to_route('filament.employee.auth.login')->withErrors([
                'provider' => ['Something went wrong. Please refresh the page and try again.'],
            ]);
        }

        /*
         * If email is present in the users datbase, update the information and login
         * Else, deny authentication
         * */
        $employee = Employee::where('email', $socialite_user->getEmail())->first();

        if (! $employee || ! $employee->canAccessEmployeePanel()) {
            return to_route('filament.employee.auth.login')->withErrors([
                'provider' => ['You are not authorized. Please contact the site administrator.'],
            ]);
        }

        $employee->name = $socialite_user->getName();
        $employee->provider_id = $socialite_user->getId();
        $employee->provider_name = $provider;
        $employee->provider_token = $socialite_user->token;
        $employee->provider_refresh_token = $socialite_user->refreshToken;
        $employee->email_verified_at = $employee->email_verified_at ?? now();
        $employee->save();

        Auth::guard('web_employee')->login($employee, $remember = true);
        return redirect()->route('filament.employee.pages.dashboard');
    }
}
