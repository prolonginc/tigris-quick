<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'phone_number' => $request->phone_number,
            'comment' => $request->comment,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

         User::whereIn('email', [
            'udsn27@gmail.com',
//            'sari.yono@lnxinc.com',
            'sabrisaadoon@gmail.com'
        ])->get()->each(function ($user) {
            $user->notify(new UserRegisteredNotification());
        });

        event(new Registered($user));

//        Auth::login($user);

        return redirect(RouteServiceProvider::PENDING);
    }
}
