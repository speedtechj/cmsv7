<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Remarkstatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class RemarkstatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_remarkstatus');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('view_remarkstatus');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_remarkstatus');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('update_remarkstatus');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('delete_remarkstatus');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_remarkstatus');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('force_delete_remarkstatus');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_remarkstatus');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('restore_remarkstatus');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_remarkstatus');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Remarkstatus $remarkstatus): bool
    {
        return $user->can('replicate_remarkstatus');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_remarkstatus');
    }
}
