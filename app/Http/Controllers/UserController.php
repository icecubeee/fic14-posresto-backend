<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    //index
    public function index(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%');
            })
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    //create
    public function create(Request $request)
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,staff,user',
        ]);

        // Simpan request...
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role; // Mengubah '$request-role' menjadi '$request->role'
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    //show
    public function show(Request $request)
    {
        return view('pages.users.show');
    }

    //edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'roles' => 'required|in:admin,staff,user',
        ]);

        // Update the request
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->roles;

        // If password is not empty
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); //

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    //destroy
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
