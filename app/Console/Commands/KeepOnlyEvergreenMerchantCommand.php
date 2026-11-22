<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Models\User;
use App\Services\Demo\DemoMerchantAccess;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KeepOnlyEvergreenMerchantCommand extends Command
{
    protected $signature = 'merchants:keep-only
                            {email=info@evergreen.com : Merchant email to keep}
                            {--force : Skip the confirmation prompt}
                            {--grant-full-access : Grant all permission modules to the kept merchant}';

    protected $description = 'Delete every merchant except the given one (default: EverGreen), optionally restoring full module access';

    public function handle(DemoMerchantAccess $demoMerchantAccess): int
    {
        $keepEmail = strtolower((string) $this->argument('email'));

        $keeper = Merchant::query()->whereRaw('LOWER(email) = ?', [$keepEmail])->first();

        if (! $keeper) {
            $this->components->error("No merchant found with email: {$keepEmail}");

            return self::FAILURE;
        }

        $toDelete = Merchant::query()
            ->where('id', '!=', $keeper->id)
            ->orderBy('email')
            ->get(['id', 'name', 'email']);

        $this->components->info("Keeping: {$keeper->name} <{$keeper->email}>");
        $this->components->warn('Will delete '.$toDelete->count().' other merchant(s):');

        foreach ($toDelete as $merchant) {
            $this->line("  - {$merchant->name} <{$merchant->email}>");
        }

        if ($toDelete->isEmpty()) {
            $this->components->info('Nothing to delete.');
        } elseif (! $this->option('force') && ! $this->confirm('Delete the merchants listed above?')) {
            $this->components->info('Cancelled.');

            return self::SUCCESS;
        }

        foreach ($toDelete as $merchant) {
            $this->forceDeleteMerchant($merchant);
            $this->components->twoColumnDetail('Deleted', "{$merchant->name} <{$merchant->email}>");
        }

        if ($this->option('grant-full-access')) {
            $demoMerchantAccess->grantFullAccess($keeper->fresh());
            $keeper->forgetPermissionModuleNames();

            $this->components->info('Full permission-module access restored for '.$keeper->email);
            $this->components->twoColumnDetail(
                'Modules',
                (string) $keeper->permissionModules()->count()
            );
        }

        $remaining = Merchant::query()->orderBy('email')->get(['name', 'email']);
        $this->components->info('Remaining merchants ('.$remaining->count().'):');

        foreach ($remaining as $merchant) {
            $this->line("  - {$merchant->name} <{$merchant->email}>");
        }

        return self::SUCCESS;
    }

    private function forceDeleteMerchant(Merchant $merchant): void
    {
        DB::transaction(function () use ($merchant): void {
            $driver = DB::getDriverName();
            $this->disableForeignKeyChecks($driver);

            try {
                $merchantId = $merchant->id;

                User::query()
                    ->where('merchant_id', $merchantId)
                    ->each(function (User $user): void {
                        $user->roles()->detach();
                        $user->permissions()->detach();
                        $user->delete();
                    });

                $merchant->roles()->detach();
                $merchant->permissions()->detach();
                $merchant->permissionModules()->detach();

                foreach ($this->merchantScopedTables() as $table) {
                    if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'merchant_id')) {
                        continue;
                    }

                    DB::table($table)->where('merchant_id', $merchantId)->delete();
                }

                DB::table('merchants')->where('id', $merchantId)->delete();
            } finally {
                $this->enableForeignKeyChecks($driver);
            }
        });
    }

    /**
     * @return list<string>
     */
    private function merchantScopedTables(): array
    {
        return [
            'add_ons',
            'asset_types',
            'assets',
            'attachments',
            'branches',
            'brand_category',
            'brand_models',
            'brands',
            'businesses',
            'cash_flows',
            'categories',
            'cold_storage_adjustments',
            'cold_storage_alert_email_settings',
            'cold_storage_bill_allocations',
            'cold_storage_bills',
            'cold_storage_chambers',
            'cold_storage_customer_legacy_references',
            'cold_storage_dispatches',
            'cold_storage_locations',
            'cold_storage_movements',
            'cold_storage_rate_cards',
            'cold_storage_receipts',
            'cold_storage_reservations',
            'cold_storage_transfers',
            'credit_reminder_templates',
            'customers',
            'demo_visitor_sessions',
            'expenses',
            'invoice_dynamic_groups',
            'merchant_credit_reminder_settings',
            'merchant_permission_modules',
            'merchant_settings',
            'notification_templates',
            'orders',
            'outbound_messages',
            'payments',
            'payrolls',
            'product_variants',
            'products',
            'purchase_returns',
            'purchases',
            'sale_returns',
            'sales',
            'vendors',
        ];
    }

    private function disableForeignKeyChecks(string $driver): void
    {
        match ($driver) {
            'sqlite' => DB::statement('PRAGMA foreign_keys = OFF'),
            'mysql', 'mariadb' => DB::statement('SET FOREIGN_KEY_CHECKS=0'),
            default => null,
        };
    }

    private function enableForeignKeyChecks(string $driver): void
    {
        match ($driver) {
            'sqlite' => DB::statement('PRAGMA foreign_keys = ON'),
            'mysql', 'mariadb' => DB::statement('SET FOREIGN_KEY_CHECKS=1'),
            default => null,
        };
    }
}
