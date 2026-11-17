<?php

namespace App\Http\Controllers;

use App\Services\Demo\TemporaryDemoCleanup;
use App\Support\DemoAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoExitController extends Controller
{
    public function __invoke(Request $request, TemporaryDemoCleanup $cleanup): RedirectResponse
    {
        $visitorSession = DemoAccount::currentVisitorSession()
            ?? DemoAccount::findVisitorSession($request);

        if ($visitorSession?->isExpired()) {
            $cleanup->purgeExpiredSession($visitorSession);

            Auth::guard('merchant')->logout();
            $request->session()->forget('demo_visitor_session_id');

            return $this->redirectWithNotice('demo_expired');
        }

        if (DemoAccount::isDemoMerchant()) {
            Auth::guard('merchant')->logout();
        }

        $request->session()->forget('demo_visitor_session_id');

        return $this->redirectWithNotice('demo_left');
    }

    private function redirectWithNotice(string $notice): RedirectResponse
    {
        // Back to the marketing site, not the merchant login (demo visitors have no login).
        return DemoAccount::redirectToLanding($notice === 'demo_expired' ? 'expired' : 'left');
    }
}
