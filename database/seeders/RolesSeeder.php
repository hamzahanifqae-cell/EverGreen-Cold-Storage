<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Only the Staff role is used for the staff portal (merchant guard).
        // Scoped to dashboard + Cold Storage — no Roles, Merchants, Staff, Businesses, or Branches.
        $role = Role::query()->firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'merchant',
        ]);

        $permissionNames = collect(['dashboard.view', 'cold_storage.*'])
            ->flatMap(function (string $permission) {
                if (str_ends_with($permission, '.*')) {
                    $module = str_replace('.*', '', $permission);

                    return Permission::query()
                        ->where('guard_name', 'merchant')
                        ->where('name', 'like', $module.'.%')
                        ->pluck('name');
                }

                return [$permission];
            })
            ->unique()
            ->values();

        $role->syncPermissions($permissionNames);

        $legacyRoleIds = Role::query()
            ->where('guard_name', 'merchant')
            ->where('name', '!=', 'Staff')
            ->pluck('id');

        if ($legacyRoleIds->isEmpty()) {
            return;
        }

        $staffRoleId = $role->id;

        $assignments = DB::table('model_has_roles')
            ->whereIn('role_id', $legacyRoleIds)
            ->get();

        foreach ($assignments as $row) {
            $alreadyHasStaff = DB::table('model_has_roles')
                ->where('role_id', $staffRoleId)
                ->where('model_type', $row->model_type)
                ->where('model_id', $row->model_id)
                ->exists();

            if ($alreadyHasStaff) {
                DB::table('model_has_roles')
                    ->where('role_id', $row->role_id)
                    ->where('model_type', $row->model_type)
                    ->where('model_id', $row->model_id)
                    ->delete();

                continue;
            }

            DB::table('model_has_roles')
                ->where('role_id', $row->role_id)
                ->where('model_type', $row->model_type)
                ->where('model_id', $row->model_id)
                ->update(['role_id' => $staffRoleId]);
        }

        DB::table('role_has_permissions')->whereIn('role_id', $legacyRoleIds)->delete();
        Role::query()->whereIn('id', $legacyRoleIds)->delete();
    }
}
