<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;

class RolePolicy
{
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
    public function view(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, Role $role)
    {
        $authIsAdmin = $authUser->hasPermission('gestion_roles');

        $roleHasManage = $role->permissions()
            ->where('nom_permission', 'gestion_roles')
            ->exists();

        $newPermissionIds = request()->input('permissions', []);
        $managePermissionId = Permission::where('nom_permission', 'gestion_roles')->value('id');
        $newPermissionsContainManage = in_array($managePermissionId, $newPermissionIds);

        // =========================================
        // RULE 1: Only admins can modify roles that contain gestion_roles
        // =========================================
        if ($roleHasManage && !$authIsAdmin) {
            throw new AuthorizationException(
                "Vous n'avez pas la permission de modifier un rôle contenant 'gestion_roles'."
            );
        }

        // =========================================
        // RULE 2: Cannot remove gestion_roles from the last role that contains it
        // =========================================
        if ($roleHasManage && !$newPermissionsContainManage) {
            $managePermission = Permission::where('nom_permission', 'gestion_roles')->first();
            $countRolesWithManage = $managePermission->roles()->count();

            if ($countRolesWithManage === 1) {
                throw new AuthorizationException(
                    "Impossible de retirer 'gestion_roles' du dernier rôle qui le possède."
                );
            }
        }

        // =========================================
        // RULE 3: Cannot remove gestion_roles from your last admin role
        // =========================================
        if ($roleHasManage && !$newPermissionsContainManage) {
            $authUserOwnsThisRole = $authUser->roles()->where('id', $role->id)->exists();
            if ($authUserOwnsThisRole) {
                $adminRolesOwned = $authUser->roles()
                    ->whereHas('permissions', function ($q) {
                        $q->where('nom_permission', 'gestion_roles');
                    })
                    ->count();

                if ($adminRolesOwned === 1) {
                    throw new AuthorizationException(
                        "Vous ne pouvez pas retirer 'gestion_roles' de votre dernier rôle administrateur."
                    );
                }
            }
        }

        return true;
    }


    public function delete(User $authUser, Role $role)
    {
        $managePermission = Permission::where('nom_permission', 'gestion_roles')->first();

        if (!$managePermission) {
            return true;
        }

        $roleHasManage = $role->permissions()->where('nom_permission', 'gestion_roles')->exists();

        if ($roleHasManage) {

            $countRolesWithManage = $managePermission->roles()->count();
            if ($countRolesWithManage === 1) {
                throw new AuthorizationException(
                    "Impossible de supprimer le dernier rôle du système qui contient 'gestion_roles'."
                );
            }

            $adminRolesOwned = $authUser->roles()
                ->whereHas('permissions', function($q){
                    $q->where('nom_permission', 'gestion_roles');
                })
                ->count();

            $ownsThisRole = $authUser->roles()->where('id', $role->id)->exists();

            if ($ownsThisRole && $adminRolesOwned === 1) {
                throw new AuthorizationException(
                    "Vous ne pouvez pas supprimer votre dernier rôle contenant 'gestion_roles'."
                );
            }

            if (!$authUser->hasPermission('gestion_roles')) {
                throw new AuthorizationException(
                    "Vous n'avez pas la permission de supprimer un rôle contenant 'gestion_roles'."
                );
            }
        }

        return true;
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
