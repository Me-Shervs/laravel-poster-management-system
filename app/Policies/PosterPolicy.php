<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Poster;
use App\Enums\UserRole;

class PosterPolicy
{

    public function update(User $user, Poster $poster): bool
    {
        return $user->role === UserRole::Admin
            || $user->id === $poster->user_id;
    }

    public function view(User $user, Poster $poster): bool
    {
        return $user->role === UserRole::Admin
            || $user->id === $poster->user_id;
    }

    public function delete(User $user, Poster $poster): bool
    {
        return $user->role === UserRole::Admin
            || $user->id === $poster->user_id;
    }
}