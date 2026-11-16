<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\Merchant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Creates (or resets) the staff login used alongside the EverGreen merchant
 * account. Safe to run more than once — it just updates the same record.
 *
 * Run with: php artisan app:create-evergreen-staff-user
 */
class CreateEvergreenStaffUserCommand extends Command
{
    protected $signature = 'app:create-evergreen-staff-user
                            {--email=staff@evergreen.com}
                            {--password=Evergreen@123}
                            {--name=EverGreen Staff}
                            {--role=Staff}
                            {--merchant-email=info@evergreen.com}';

    protected $description = 'Create or update the staff login for the EverGreen merchant';

    public function handle(): int
    {
        $merchant = Merchant::query()->where('email', $this->option('merchant-email'))->first();

        if (! $merchant) {
            $this->error('Merchant '.$this->option('merchant-email').' not found.');

            return self::FAILURE;
        }

        $email = (string) $this->option('email');

        $user = User::query()->firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->id = (string) Str::uuid();
        }

        $user->fill([
            'name' => (string) $this->option('name'),
            'merchant_id' => $merchant->id,
            'password' => (string) $this->option('password'),
            'status' => 'verified',
            'is_active' => true,
        ])->save();

        $role = Role::query()->where('name', $this->option('role'))->where('guard_name', 'merchant')->first();

        if ($role) {
            $user->syncRoles([$role]);
        } else {
            $this->warn('Role "'.$this->option('role').'" not found — user created without a role.');
        }

        $business = Business::query()->where('merchant_id', $merchant->id)->orderBy('name')->first();
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

        $this->info('Staff login ready: '.$email);
        $this->line('Role: '.($role?->name ?? 'none')
            .' | Business: '.($business?->name ?? 'none')
            .' | Branch: '.($branch?->name ?? 'none'));

        return self::SUCCESS;
    }
}
