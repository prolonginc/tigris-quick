<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function __construct()
    {

    }
    public function index()
    {
        if(! $this->isAdmin()) {
            abort(403);
        }

        $approvedUsers = User::where('is_approved',1)->get();
        $pendingUsers = User::where('is_approved',0)->get();
        return view('admin.index')->with(compact(['approvedUsers','pendingUsers']));
    }

    public function approve(User $user)
    {
        $user->is_approved = 1;
        $user->save();
        $user->notify(new UserApprovedNotification());
        return redirect(route('admin.index'));

    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect(route('admin.index'));

    }

    protected function isAdmin()
    {
        $email =  Auth::user()->email;
        return (in_array($email,
        [
            'udsn27@gmail.com',
            'sari.yono@lnxinc.com',
            'sabrisaadoon@gmail.com'
        ]
        ));
    }
}
