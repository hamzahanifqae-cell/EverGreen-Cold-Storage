<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Wipe operational CRM / cold-storage data while keeping login credentials.
 *
 * Kept: merchants, users (staff), roles/permissions, permission modules,
 * countries/cities, and Laravel system tables.
 *
 * Run with: php artisan app:production-credentials-only-clear --force
 */
class ProductionCredentialsOnlyClearCommand extends Command
{
    protected $signature = 'app:production-credentials-only-clear
                            {--force : Skip the confirmation prompt}
                            {--allow-local : Allow running in the local environment}';

    protected $description = 'Clear all CRM data except merchant/staff credentials and roles';

    /**
     * Tables that must never be wiped by this command.
     *
     * @var list<string>
     */
    private array $tablesToKeep = [
        'merchants',
        'users',
        'roles',
        'permissions',
        'role_has_permissions',
        'model_has_roles',
        'model_has_permissions',
        'permission_modules',
        'merchant_permission_modules',
        'countries',
        'cities',
        'migrations',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'sessions',
        'password_reset_tokens',
    ];

    public function handle(): int
    {
        if (app()->environment('local') && ! $this->option('allow-local')) {
            $this->error('Refusing to run against a local environment without --allow-local.');

            return self::FAILURE;
        }

        $tablesToClear = $this->tablesToClear();

        $this->warn('This will permanently delete all rows from '.count($tablesToClear).' tables.');
        $this->line('Kept: merchants, users (staff), roles/permissions, permission modules, countries, cities.');
        $this->line('Removed: businesses, branches, products, customers, cold storage, and all other CRM data.');

        if (! $this->option('force') && ! $this->confirm('Are you sure you want to continue?')) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $driver = DB::getDriverName();
        $this->disableForeignKeyChecks($driver);

        $cleared = 0;

        try {
            foreach ($tablesToClear as $table) {
                if ($driver === 'sqlite') {
                    DB::table($table)->delete();
                } else {
                    DB::table($table)->truncate();
                }

                $cleared++;
                $this->line("Cleared: {$table}");
            }
        } finally {
            $this->enableForeignKeyChecks($driver);
        }

        $resetCash = Merchant::query()->update([
            'cash_in_hand' => 0,
            'cash_in_bank' => 0,
        ]);

        $this->line("Reset cash accounts on {$resetCash} merchant(s).");
        $this->components->twoColumnDetail('Merchants kept', (string) Merchant::query()->count());
        $this->components->twoColumnDetail('Staff kept', (string) DB::table('users')->count());
        $this->info("Done. Cleared {$cleared} tables.");

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function tablesToClear(): array
    {
        $keep = array_fill_keys($this->tablesToKeep, true);
        $tables = [];

        foreach (Schema::getTableListing() as $table) {
            $name = str_contains($table, '.') ? explode('.', $table, 2)[1] : $table;

            if (isset($keep[$name])) {
                continue;
            }

            $tables[] = $name;
        }

        sort($tables);

        return $tables;
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
