<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Business;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductionCredentialsOnlyClearCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_clears_business_data_but_keeps_merchant_and_staff_credentials(): void
    {
        $merchant = Merchant::query()->create([
            'id' => Str::uuid()->toString(),
            'email' => 'info@evergreen.com',
            'name' => 'EverGreen Cold Storage',
            'address_line_1' => 'Pakistan',
            'city' => 'Karachi',
            'status' => Merchant::STATUS_VERIFIED,
            'is_active' => true,
            'password' => 'Secret@123456',
            'cash_in_hand' => 5000,
            'cash_in_bank' => 9000,
        ]);

        $staff = User::query()->create([
            'id' => Str::uuid()->toString(),
            'merchant_id' => $merchant->id,
            'name' => 'EverGreen Staff',
            'email' => 'staff@evergreen.com',
            'password' => 'Secret@123456',
            'status' => User::STATUS_VERIFIED,
            'is_active' => true,
        ]);

        $business = Business::query()->create([
            'id' => Str::uuid()->toString(),
            'merchant_id' => $merchant->id,
            'name' => 'Solar Systems',
            'status' => 'active',
        ]);

        if (Schema::hasTable('branches')) {
            Branch::query()->create([
                'id' => Str::uuid()->toString(),
                'merchant_id' => $merchant->id,
                'business_id' => $business->id,
                'name' => 'Solar Systems Branch 1',
                'status' => Branch::STATUS_VERIFIED,
                'is_active' => true,
            ]);
        }

        $this->artisan('app:production-credentials-only-clear', [
            '--force' => true,
            '--allow-local' => true,
        ])->assertSuccessful();

        $this->assertDatabaseHas('merchants', [
            'id' => $merchant->id,
            'email' => 'info@evergreen.com',
            'cash_in_hand' => 0,
            'cash_in_bank' => 0,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $staff->id,
            'email' => 'staff@evergreen.com',
        ]);
        $this->assertDatabaseMissing('businesses', ['id' => $business->id]);

        if (Schema::hasTable('branches')) {
            $this->assertDatabaseCount('branches', 0);
        }
    }
}
