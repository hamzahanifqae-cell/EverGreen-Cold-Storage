<?php

namespace Tests\Feature;

use App\Models\Merchant;
use Database\Seeders\PermissionsModulesSeeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KeepOnlyEvergreenMerchantCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_other_merchants_and_can_grant_full_module_access(): void
    {
        (new PermissionsSeeder)->run();
        (new RolesSeeder)->run();
        (new PermissionsModulesSeeder)->run();

        $keeper = $this->merchant('info@evergreen.com', 'EverGreen Cold Storage');
        $halaynoor = $this->merchant('info@halaynoor.com', 'Halaynoor');
        $demo = $this->merchant('demo-'.str_repeat('a', 32).'@crmdemo.com', 'EverGreen Cold Storage Demo');

        $this->artisan('merchants:keep-only', [
            'email' => 'info@evergreen.com',
            '--force' => true,
            '--grant-full-access' => true,
        ])->assertSuccessful();

        $this->assertDatabaseHas('merchants', ['email' => 'info@evergreen.com']);
        $this->assertDatabaseMissing('merchants', ['id' => $halaynoor->id]);
        $this->assertDatabaseMissing('merchants', ['id' => $demo->id]);

        $keeper->refresh()->forgetPermissionModuleNames();

        $this->assertTrue($keeper->hasPermissionModule('products'));
        $this->assertTrue($keeper->hasPermissionModule('customers'));
        $this->assertSame(1, Merchant::query()->count());
    }

    private function merchant(string $email, string $name): Merchant
    {
        return Merchant::query()->create([
            'id' => Str::uuid()->toString(),
            'email' => $email,
            'name' => $name,
            'address_line_1' => 'Pakistan',
            'city' => 'Karachi',
            'status' => Merchant::STATUS_VERIFIED,
            'is_active' => true,
            'password' => 'Secret@123456',
        ]);
    }
}
