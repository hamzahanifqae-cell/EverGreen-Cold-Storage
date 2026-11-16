<?php

namespace App\Filament\Resources\ColdStorageDispatches\Pages;

use App\Exceptions\ColdStorageException;
use App\Filament\ColdStorage\ColdStorageActions;
use App\Filament\Resources\ColdStorageBills\ColdStorageBillResource;
use App\Filament\Resources\ColdStorageDispatches\ColdStorageDispatchResource;
use App\Models\ColdStorageBill;
use App\Services\ColdStorage\BillingService;
use App\Services\ColdStorage\DispatchService;
use App\Support\ColdStorageAccess;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewColdStorageDispatch extends ViewRecord
{
    protected static string $resource = ColdStorageDispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ColdStorageActions::post(DispatchService::class),
            ColdStorageActions::print('dispatch'),
            $this->billThisWithdrawal(),
            ColdStorageActions::cancel(DispatchService::class),
        ];
    }

    private function billThisWithdrawal(): Action
    {
        return Action::make('billThisWithdrawal')
            ->label('Bill this withdrawal')
            ->icon('heroicon-o-receipt-percent')
            ->color('success')
            ->visible(fn (): bool => $this->record->status === 'posted'
                && ColdStorageAccess::can('create')
                && ! ColdStorageBill::query()
                    ->where('dispatch_id', $this->record->id)
                    ->whereIn('status', ['draft', 'posted'])
                    ->exists())
            ->modalHeading('Bill this withdrawal')
            ->modalDescription('Creates and posts a storage bill for this return, then records the full amount into cash in hand or bank.')
            ->schema([
                Select::make('method')
                    ->label('Receive payment into')
                    ->options([
                        'cash_in_hand' => 'Cash in hand',
                        'cash_in_bank' => 'Bank',
                    ])
                    ->required()
                    ->default('cash_in_hand')
                    ->helperText('The billed rent is posted and collected into this account.'),
            ])
            ->action(function (array $data, BillingService $billing): void {
                try {
                    $bill = $billing->billDispatchAndCollect(
                        $this->record,
                        (string) $data['method'],
                        ColdStorageAccess::actorId(),
                    );

                    $accountLabel = $billing->cashAccountForMethod((string) $data['method']) === 'cash_in_bank'
                        ? 'Bank'
                        : 'Cash in hand';

                    Notification::make()
                        ->title('Withdrawal billed and collected')
                        ->body('Total '.config('cold-storage.currency').' '.number_format((float) $bill->total_amount, 2).' recorded to '.$accountLabel.'.')
                        ->success()
                        ->send();

                    $this->redirect(ColdStorageBillResource::getUrl('view', ['record' => $bill]));
                } catch (ColdStorageException $exception) {
                    Notification::make()->title('Could not create bill')->body($exception->getMessage())->danger()->send();
                }
            });
    }
}
