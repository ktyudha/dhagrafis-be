<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['view all events', 'view own events']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): Response
    {
        if ($user->can('view all events')) return Response::allow();

        if ($user->can('view own events')) {
            return $user->id === $event->author_id
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
        return $user->can('create events');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): Response
    {
        if ($user->can('edit all events')) return Response::allow();

        if ($user->can('edit own events')) {
            return $user->id === $event->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        if ($user->can('delete all events')) return Response::allow();

        if ($user->can('delete own events')) {
            return $user->id === $event->author_id
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
        return $user->can('publish events');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user): bool
    {
        return $user->can('unpublish events');
    }
}
