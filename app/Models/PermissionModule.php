<?php

namespace App\Models;

use App\Support\UiModules;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PermissionModule extends Model implements Auditable
{
    use HasUuids;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'module',
        'label',
        //        'is_enabled',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function merchants()
    {
        return $this->belongsToMany(
            Merchant::class,
            'merchant_permission_modules'
        )->withTimestamps();
    }

    public static function isEnabledForMerchant(string $module, string $merchantId): bool
    {
        return self::where('module', $module)
            ->whereHas('merchants', function ($query) use ($merchantId) {
                $query->where('merchant_id', $merchantId);
            })
            ->exists();
    }

    public static function enabledForCurrentMerchant(): array
    {
        $user = Filament::auth()->user();

        // Merchant owner: full catalog (same idea as Merchant::hasPermissionTo always true).
        if ($user instanceof Merchant) {
            return self::query()->orderBy('label')->pluck('module')->all();
        }

        // Staff panel → inherit from merchant's enabled modules
        if ($user instanceof User && $user->merchant) {
            return $user->merchant->permissionModuleNames();
        }

        return [];
    }

    public static function isEnabledForCurrentMerchant(string $module): bool
    {
        $user = Filament::auth()->user();

        // Merchant owner always has full module access; the pivot only
        // gates what staff under that merchant can see.
        if ($user instanceof Merchant) {
            return true;
        }

        // Staff: UI-enabled modules are gated by role permissions alone, so a
        // merchant can grant Products/Customers from Roles without the pivot UI.
        if ($user instanceof User) {
            if (in_array($module, UiModules::staffAssignablePermissionModules(), true)) {
                return true;
            }

            return $user->merchant?->hasPermissionModule($module) ?? false;
        }

        return false;
    }
}
