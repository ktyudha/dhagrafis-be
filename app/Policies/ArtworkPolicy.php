<?php

namespace App\Policies;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArtworkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['view all artworks', 'view own artworks']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Artwork $artwork): Response
    {
        if ($user->can('view all artworks')) return Response::allow();

        if ($user->can('view own artworks')) {
            return $user->id === $artwork->author_id
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
        return $user->can('create artworks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Artwork $artwork): Response
    {
        if ($user->can('edit all artworks')) return Response::allow();

        if ($user->can('edit own artworks')) {
            return $user->id === $artwork->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Artwork $artwork): Response
    {
        if ($user->can('delete all artworks')) return Response::allow();

        if ($user->can('delete own artworks')) {
            return $user->id === $artwork->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user): bool
    {
        return $user->can('publish artworks');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user): bool
    {
        return $user->can('unpublish artworks');
    }
}
