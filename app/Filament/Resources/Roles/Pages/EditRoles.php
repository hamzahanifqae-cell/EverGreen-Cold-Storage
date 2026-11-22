<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RolesResource;
use App\Support\UiModules;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditRoles extends EditRecord
{
    protected static string $resource = RolesResource::class;

    public function getTitle(): string
    {
        $name = (string) ($this->record?->name ?? '');

        return 'Edit '.Str::limit($name, 30);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->color('danger')
                ->visible(fn () => auth(Filament::getCurrentPanel()->getAuthGuard())->user()?->hasPermissionTo('roles_permissions.delete', Filament::getCurrentPanel()->getAuthGuard())),
        ];
    }

    protected function resolveRecord($key): Model
    {
        return static::getResource()::getEloquentQuery()->with('permissions')->findOrFail($key);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $enabledModules = UiModules::staffAssignablePermissionModules();

        $permissions = $this->record->permissions
            ->pluck('name')
            ->toArray();

        $permissionsData = [];

        // Initialize all modules shown on the Roles form (Products, Customers, Cold storage, …)
        foreach ($enabledModules as $module) {
            $permissionsData[$module] = [
                'enabled' => false,
                'view' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ];
        }

        // Map existing permissions
        foreach ($permissions as $permission) {
            [$module, $action] = explode('.', $permission);

            if (isset($permissionsData[$module][$action])) {
                $permissionsData[$module][$action] = true;
                $permissionsData[$module]['enabled'] = true; // 🔑 enable module
            }
        }

        $data['permissions'] = $permissionsData;

        return $data;
    }

    public static function getPermissionModules(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'users' => 'Users',
            'admins' => 'Admins',
            'settings' => 'Settings',
            'roles_permissions' => 'Roles & Permissions',
            'merchants' => 'Merchants',
            'merchant_settings' => 'Merchant Settings',
            'businesses' => 'Businesses',
            'orders' => 'Orders',
            'branches' => 'Branches',
            'categories' => 'Categories',
            'customers' => 'Customers',
            'audits' => 'Audits',
        ];
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data
    ): Model {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        return DB::transaction(function () use ($record, $data, $permissions) {
            $record->update([
                'name' => $data['name'],
            ]);

            RolesResource::afterUpdate($record, $permissions);

            return $record;
        }, 3);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
