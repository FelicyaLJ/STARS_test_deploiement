<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserPolicy
{
    use HandlesAuthorization;

    protected $mainAdminEmail = 'president@stars.ca';

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser, array $roleIds = []): bool
    {
        // If no roles are being assigned, allow creation
        if (empty($roleIds)) {
            return true;
        }

        // Get IDs of roles containing 'gestion_roles' permission
        $adminRoleIds = Role::whereHas('permissions', function ($q) {
            $q->where('nom_permission', 'gestion_roles');
        })->pluck('id')->toArray();

        // Check if any of the roles being assigned are admin roles
        $assigningAdminRoles = count(array_intersect($roleIds, $adminRoleIds)) > 0;

        // If user is trying to assign admin roles without permission
        if ($assigningAdminRoles && !$authUser->hasPermission('gestion_roles')) {
            throw new AuthorizationException(
                "Vous n'avez pas la permission d'attribuer des rôles administrateurs."
            );
        }

        return true;
    }

    public function update(User $authUser, User $targetUser)
    {
        if ($targetUser->email === $this->mainAdminEmail && $authUser->id !== $targetUser->id) {
            throw new AuthorizationException(
                "L'utilisateur principal ne peut être modifié que par lui-même."
            );
        }

        $managePermission = Permission::where('nom_permission', 'gestion_roles')->first();

        // If no gestion_roles permission exists, nothing to protect
        if (!$managePermission) return true;

        $targetIsAdmin = $targetUser->hasPermission('gestion_roles');
        $editorIsAdmin = $authUser->hasPermission('gestion_roles');

        // Non-admin cannot edit an admin
        if ($targetIsAdmin && !$editorIsAdmin) {
            throw new AuthorizationException(
                "Vous n'avez pas la permission de modifier un utilisateur possédant 'gestion_roles'."
            );
        }

        $requestRoles = request()->input('roles', []);
        $oldRoles = $targetUser->roles()->pluck('id')->toArray();
        $authUserOwnsTarget = $authUser->id === $targetUser->id;

        // Fetch the gestion_roles permission
        $managePermission = Permission::where('nom_permission', 'gestion_roles')->first();

        // If gestion_roles permission exists
        if ($managePermission) {

            // 1️⃣ Check if user previously had gestion_roles roles
            $hadManageRole = Role::whereIn('id', $oldRoles)
                ->whereHas('permissions', fn($q) => $q->where('id', $managePermission->id))
                ->exists();

            // Roles after update (fallback to oldRoles if frontend sends nothing)
            $newRoleIds = $requestRoles ?: $oldRoles;

            // 2️⃣ Check if any new roles contain gestion_roles
            $newManageRoles = Role::whereIn('id', $newRoleIds)
                ->whereHas('permissions', fn($q) => $q->where('id', $managePermission->id))
                ->pluck('id');

            // ---- Rule A: Prevent removing all own admin roles ----
            if ($authUserOwnsTarget && $hadManageRole && $newManageRoles->isEmpty()) {
                throw new AuthorizationException(
                    "Vous ne pouvez pas supprimer tous vos rôles administrateurs ; vous devez garder au moins un rôle contenant 'gestion_roles'."
                );
            }

            // ---- Rule B: Prevent self-assigning admin role if previously not admin ----
            if ($authUserOwnsTarget && !$hadManageRole && $newManageRoles->isNotEmpty()) {
                throw new AuthorizationException(
                    "Vous ne pouvez pas vous attribuer un rôle contenant 'gestion_roles' si vous n'aviez aucun rôle administrateur auparavant."
                );
            }
        }


        return true;
    }

    public function delete(User $authUser, User $targetUser)
    {
        if ($targetUser->email === $this->mainAdminEmail) {
            throw new AuthorizationException(
                "L'utilisateur principal ne peut pas être supprimé."
            );
        }

        $managePermission = Permission::where('nom_permission', 'gestion_roles')->first();
        if (!$managePermission) return true;

        $targetIsAdmin = $targetUser->hasPermission('gestion_roles');
        $editorIsAdmin = $authUser->hasPermission('gestion_roles');

        // Non-admin cannot delete an admin
        if ($targetIsAdmin && !$editorIsAdmin) {
            throw new AuthorizationException(
                "Vous n'avez pas la permission de supprimer un utilisateur possédant 'gestion_roles'."
            );
        }

        if ($targetIsAdmin) {
            // Count all users with gestion_roles
            $usersWithManageRoles = $managePermission->roles->flatMap(fn($r) => $r->users)->unique('id');

            if ($usersWithManageRoles->count() === 1 && $usersWithManageRoles->first()->id === $targetUser->id) {
                throw new AuthorizationException(
                    "Impossible de supprimer cet utilisateur car il est le dernier à posséder un rôle avec 'gestion_roles'."
                );
            }
        }

        return true;
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
