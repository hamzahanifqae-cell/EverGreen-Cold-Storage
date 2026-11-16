<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\Merchant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffsSeeder extends Seeder
{
    public function run(): void
    {
        $primaryMerchant = Merchant::where('email', 'info@evergreen.com')->first();
        $halaynoor = Merchant::where('email', 'info@halaynoor.com')->first();

        if ($primaryMerchant) {
            $this->createUser(
                merchant: $primaryMerchant,
                name: 'EverGreen Staff',
                email: 'staff@evergreen.com',
                roleName: 'Staff',
                password: 'Evergreen@123',
            );

            $this->createUser(
                merchant: $primaryMerchant,
                name: 'Flowdesk Admin',
                email: 'admin@flowdesk.com',
                roleName: 'Staff'
            );

            $this->createUser(
                merchant: $primaryMerchant,
                name: 'Flowdesk Supervisor',
                email: 'supervisor@flowdesk.com',
                roleName: 'Staff'
            );
        }

        if ($halaynoor) {
            $this->createUser(
                merchant: $halaynoor,
                name: 'Halaynoor Admin',
                email: 'admin@halaynoor.com',
                roleName: 'Staff'
            );

            $this->createUser(
                merchant: $halaynoor,
                name: 'Halaynoor Support',
                email: 'support@halaynoor.com',
                roleName: 'Staff'
            );
        }
    }

    private function createUser(
        Merchant $merchant,
        string $name,
        string $email,
        string $roleName,
        string $password = 'DD@2025@DD',
    ): void {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'id' => Str::uuid(),
                'name' => $name,
                'merchant_id' => $merchant->id,
                'password' => $password,
                'status' => 'verified',
                'is_active' => true,
            ]
        );

        $role = Role::where('name', $roleName)->where('guard_name', 'merchant')->first();
        if ($role) {
            $user->assignRole($role);
        }

        $business = Business::query()
            ->where('merchant_id', $merchant->id)
            ->orderBy('name')
            ->first();

        $branch = Branch::query()
            ->where('merchant_id', $merchant->id)
            ->when($business, fn ($query) => $query->where('business_id', $business->id))
            ->orderBy('name')
            ->first()
            ?? Branch::query()->where('merchant_id', $merchant->id)->orderBy('name')->first();

        if ($business) {
            BusinessUser::query()->firstOrCreate(
                ['user_id' => $user->id, 'business_id' => $business->id],
                ['id' => (string) Str::uuid()],
            );
        }

        if ($branch) {
            BranchUser::query()->firstOrCreate(
                ['user_id' => $user->id, 'branch_id' => $branch->id],
                ['id' => (string) Str::uuid()],
            );
        }
    }
}
