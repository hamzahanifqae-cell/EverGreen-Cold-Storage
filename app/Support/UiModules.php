<?php

namespace App\Support;

class UiModules
{
    public static function enabled(?string $module): bool
    {
        if ($module === null || $module === '') {
            return true;
        }

        if ((bool) config("ui-modules.enabled.{$module}", true)) {
            return true;
        }

        // Only the modules listed in peekableModules() can be revealed again
        // by the "show hidden modules" checkbox in the sidebar, for the
        // current browser session only. Everything else stays out of the UI
        // regardless of the checkbox.
        if (! in_array($module, self::peekableModules(), true)) {
            return false;
        }

        return self::hiddenModulesVisible();
    }

    public static function hiddenModulesVisible(): bool
    {
        return (bool) session('show_hidden_modules', false);
    }

    /**
     * @return list<string>
     */
    public static function peekableModules(): array
    {
        return [
            'cold_storage_adjustments',
        ];
    }

    /**
     * Map dashboard overview cards to ui-module keys.
     *
     * @return array<string, list<string>>
     */
    public static function dashboardOverviewModuleMap(): array
    {
        return [
            'sales' => ['sales'],
            'purchases' => ['purchases'],
            'profit_loss' => ['sales', 'purchases'],
            'stock' => ['stock_report'],
            'inventory_movement' => ['inventory_movement_report'],
            'expenses' => ['expenses'],
            'funds' => ['cash_flows', 'sales'],
            'cash_flow' => ['cash_flows'],
        ];
    }

    public static function dashboardOverviewEnabled(string $overviewKey): bool
    {
        $required = self::dashboardOverviewModuleMap()[$overviewKey] ?? [];

        if ($required === []) {
            return true;
        }

        foreach ($required as $module) {
            if (self::enabled($module)) {
                return true;
            }
        }

        return false;
    }

    public static function anyDashboardOverviewEnabled(): bool
    {
        foreach (array_keys(self::dashboardOverviewModuleMap()) as $key) {
            if (self::dashboardOverviewEnabled($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Permission-module keys a merchant can grant to staff on the Roles form.
     * Driven by config/ui-modules.php so hidden CRM features stay off the checklist.
     *
     * @return list<string>
     */
    public static function staffAssignablePermissionModules(): array
    {
        $map = [
            'dashboard' => 'dashboard',
            'staff' => 'users',
            'roles' => 'roles_permissions',
            'merchants' => 'merchants',
            'businesses' => 'businesses',
            'branches' => 'branches',
            'customers' => 'customers',
            'products' => 'products',
            'categories' => 'categories',
            'cold_storage' => 'cold_storage',
        ];

        $modules = [];

        foreach ($map as $uiKey => $permissionModule) {
            if (self::enabled($uiKey)) {
                $modules[] = $permissionModule;
            }
        }

        return array_values(array_unique($modules));
    }
}
