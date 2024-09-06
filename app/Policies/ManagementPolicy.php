<?php

namespace App\Policies;

use App\Models\Management;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManagementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['management read']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Management $management): Response
    {
        if ($user->can('management read')) return Response::allow();
        if ($user->can('view own management')) {
            return $user->id === $management->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }
        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create articles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Management $management): Response
    {
        if ($user->can('management update')) return Response::allow();

        if ($user->can('edit own articles')) {
            return $user->id === $management->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Management $management): Response
    {
        if ($user->can('management delete')) return Response::allow();

        if ($user->can('delete own articles')) {
            return $user->id === $management->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }
}
