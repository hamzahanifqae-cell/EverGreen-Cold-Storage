<?php

namespace Tests\Feature;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\PermissionModules\PermissionModuleResource;
use App\Filament\Resources\Products\ProductResource;
use App\Support\UiModules;
use Tests\TestCase;

class UiModulesPermissionModulesVisibleTest extends TestCase
{
    public function test_permission_modules_products_and_customers_are_enabled_in_ui_config(): void
    {
        $this->assertTrue(UiModules::enabled('permission_modules'));
        $this->assertTrue(UiModules::enabled('products'));
        $this->assertTrue(UiModules::enabled('customers'));
    }

    public function test_merchant_panel_resources_register_navigation_for_those_modules(): void
    {
        $this->assertTrue(PermissionModuleResource::shouldRegisterNavigation());
        $this->assertTrue(ProductResource::shouldRegisterNavigation());
        $this->assertTrue(CustomerResource::shouldRegisterNavigation());
    }
}
