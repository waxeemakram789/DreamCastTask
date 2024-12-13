<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create()
    {
        $roles = Role::all();
        return view('users', compact('roles')); 
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[6-9]\d{9}$/',
            'description' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;
        if ($request->hasFile('profile_image')) {
            $filePath = $request->file('profile_image')->store('profile_images', 'public');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'role_id' => $request->role_id,
            'profile_image' => $filePath,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function index()
    {
        $users = User::with('role')->get();
        return response()->json($users);
    }
}
