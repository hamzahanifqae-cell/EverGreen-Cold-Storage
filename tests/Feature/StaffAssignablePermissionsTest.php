<?php

namespace Tests\Feature;

use App\Filament\Resources\Roles\RolesResource;
use App\Models\Merchant;
use App\Models\PermissionModule;
use App\Models\User;
use App\Support\UiModules;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StaffAssignablePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_matrix_includes_customers_and_products_for_merchant_owners(): void
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

        $this->actingAs($merchant, 'merchant');
        Filament::setCurrentPanel(Filament::getPanel('merchant'));

        $modules = UiModules::staffAssignablePermissionModules();
        $matrix = RolesResource::getAssignablePermissionMatrix();

        $this->assertContains('customers', $modules);
        $this->assertContains('products', $modules);
        $this->assertContains('cold_storage', $modules);

        $this->assertArrayHasKey('customers', $matrix);
        $this->assertArrayHasKey('products', $matrix);
        $this->assertSame(['view', 'create', 'update', 'delete'], $matrix['customers']);
        $this->assertSame(['view', 'create', 'update', 'delete'], $matrix['products']);
    }

    public function test_staff_can_use_ui_enabled_modules_without_merchant_pivot_rows(): void
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

        $staff = User::query()->create([
            'id' => Str::uuid()->toString(),
            'merchant_id' => $merchant->id,
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => 'Secret@123456',
            'status' => User::STATUS_VERIFIED,
            'is_active' => true,
        ]);

        $this->actingAs($staff, 'staff');
        Filament::setCurrentPanel(Filament::getPanel('user'));

        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('products'));
        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('customers'));
        $this->assertTrue(PermissionModule::isEnabledForCurrentMerchant('cold_storage'));
    }
}
