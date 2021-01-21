<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request) {
        $this->validate($request, [
            'name'    => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        try {

            $this->user->name = $request->input('name');
            $this->user->email = $request->input('email');
            $password = $request->input('password');
            $this->user->password = Hash::make($password);
            $this->user->hash = Str::uuid();

            $this->user->save();

            return response()->json($this->user, 201);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error on register a new user!', 409]);
        }
    }

    public function index() {

        $users = User::all();
        return response()->json($users);

    }

    //
}
