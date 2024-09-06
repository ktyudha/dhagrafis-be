<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, function (Builder $query, string $key) {
                $query->search($key);
            })
            ->when($request->has('verified'), function (Builder $query) use ($request) {
                $request->verified
                    ? $query->whereNotNull('email_verified_at')
                    : $query->whereNull('email_verified_at');
            })
            ->when($request->role, function (Builder $query, string $role) {
                $query->role($role);
            })
            ->paginate(10);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['email_verified_at'] = $request->input('email_verified_at') ? now() : null;

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('users');
        }

        $user = User::create($validated);
        $user->assignRole($validated['role']);

        return response()->json([
            'success' => true,
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user->update($validated);
        $user->syncRoles($validated['role']);

        return response()->json([
            'success' => true,
            'user' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::delete($user->photo);
        }
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
