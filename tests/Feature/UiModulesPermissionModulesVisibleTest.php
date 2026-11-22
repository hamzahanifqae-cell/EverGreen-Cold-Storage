<?php

namespace Tests\Feature;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\PermissionModules\PermissionModuleResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Roles\RolesResource;
use App\Models\Merchant;
use App\Models\PermissionModule;
use App\Support\UiModules;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UiModulesPermissionModulesVisibleTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_modules_stay_hidden_while_roles_products_and_customers_are_enabled(): void
    {
        $this->assertFalse(UiModules::enabled('permission_modules'));
        $this->assertTrue(UiModules::enabled('roles'));
        $this->assertTrue(UiModules::enabled('products'));
        $this->assertTrue(UiModules::enabled('customers'));
    }

    public function test_merchant_panel_hides_permission_modules_but_registers_roles_products_and_customers(): void
    {
        $this->assertFalse(PermissionModuleResource::shouldRegisterNavigation());
        $this->assertTrue(RolesResource::shouldRegisterNavigation());
        $this->assertTrue(ProductResource::shouldRegisterNavigation());
        $this->assertTrue(CustomerResource::shouldRegisterNavigation());
    }

    public function test_merchant_owner_can_view_roles_products_and_customers_even_without_pivot_rows(): void
    {
        $merchant = Merchant::query()->create([
            'id' => Str::uuid()->toString(),
            'email' => 'owner@example.com',
            'name' => 'Owner',
            'address_line_1' => 'Pakistan',
            'city' => 'Karachi',
            'status' => Merchant::STATUS_VERIFIED,
            'is_active' => true,
            'password' => 'Secret@123456',
        ]);

        PermissionModule::query()->create([
            'id' => Str::uuid()->toString(),
            'module' => 'products',
            'label' => 'Products',
        ]);

        $this->actingAs($merchant, 'merchant');
        Filament::setCurrentPanel(Filament::getPanel('merchant'));

        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('products'));
        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('customers'));
        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('roles_permissions'));

        $this->assertTrue(ProductResource::canViewAny());
        $this->assertTrue(CustomerResource::canViewAny());
        $this->assertTrue(RolesResource::canViewAny());
        $this->assertFalse(PermissionModuleResource::shouldRegisterNavigation());
    }
}
