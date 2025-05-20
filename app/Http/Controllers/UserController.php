<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::where('id', '!=', 1)->orderBy('name')->paginate(10);
        // return view('user.index', compact('users'));

        // $search = request('search');
        // if ($search) {
        //     $users = User::with('todos')->where(function ($query) use ($search) {
        //             $query->where('name', 'like', '%' . $search . '%')
        //                 ->orWhere('email', 'like', '%' . $search . '%');
        //         })
        //         ->orderBy('name')
        //         ->where('id', '!=', 1)
        //         ->paginate(20)
        //         ->withQueryString();
        // } else {
        //     $users = User::where('id', '!=', 1)
        //         ->orderBy('name')
        //         ->paginate(20);
        // }

        $search = request('search');
        if ($search) {
            $users = User::with('todos')->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->paginate(20);
        } else {
            $users = User::with('todos')->where('id', '!=', 1)
                ->orderBy('name')
                ->paginate(10);
        }

        return view('user.index', compact('users'));
    }

    public function makeadmin(User $user)
    {
        $user->timestamps = false;
        $user->is_admin = true;
        $user->save();
        return back()->with('success', 'User made admin successfully.');
    }
    public function removeadmin(User $user)
    {
       if ($user->id != 1) {
            $user->timestamps = false;
            $user->is_admin = false;
            $user->save();

            if (auth()->id() == $user->id) {
                auth()->logout();
                return redirect('/');
            }

            return back()->with('success', 'User removed from admin successfully.');
        }
        return redirect()->route('user.index');
    }


    public function destroy(User $user)
    {
        if ($user->id != 1) {
            $user->delete();
            return back()->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('user.index')->with('error', 'Delete user failed!');
        }
    }
}
