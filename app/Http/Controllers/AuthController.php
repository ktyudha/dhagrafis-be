<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => new AuthResource($request->user())
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Content Creator');

        event(new Registered($user));

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'User has been successfully registered',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        return response()->json([
            'success' => true,
            'message' => 'User has successfully logged in',
            'user' => new AuthResource($request->user()),
            'token' => $request->user()->createToken('auth_token')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User has successfully logged out'
        ]);
    }

    public function profile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'password' => ['required', Password::defaults()],
            'phone' => ['nullable', 'numeric', Rule::unique('users')->ignore(request()->user()->id)],
            'photo' => ['nullable', 'file', 'image'],
        ]);

        if ($request->hasFile('photo')) {
            if ($request->user()->photo) {
                Storage::disk('public')->delete($request->user()->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user = $request->user()->update($validated);

        return response()->json([
            'success' => true,
            'user' => new AuthResource($user)
        ]);
    }
}
