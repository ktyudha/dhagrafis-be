<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAny(['view all articles', 'view own articles']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): Response
    {
        if ($user->can('view all articles')) return Response::allow();

        if ($user->can('view own articles')) {
            return $user->id === $article->author_id
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
    public function update(User $user, Article $article): Response
    {
        if ($user->can('edit all articles')) return Response::allow();

        if ($user->can('edit own articles')) {
            return $user->id === $article->author_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): Response
    {
        if ($user->can('delete all articles')) return Response::allow();

        if ($user->can('delete own articles')) {
            return $user->id === $article->author_id
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
        return $user->can('publish article');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user): bool
    {
        return $user->can('unpublish article');
    }
}
