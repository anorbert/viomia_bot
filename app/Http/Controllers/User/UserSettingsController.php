<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use App\Models\Account;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Storage;
use Carbon\Carbon;

class UserSettingsController extends Controller
{
    /**
     * Show the settings dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('users.settings.index', compact('user'));
    }

    /**
     * Show account settings
     */
    public function account()
    {
        $user = Auth::user();
        
        return view('users.settings.account', compact('user'));
    }

    /**
     * Update account settings
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Account settings updated successfully');
    }

    /**
     * Show security settings
     */
    public function security()
    {
        $user = Auth::user();
        $loginSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get();

        return view('users.settings.security', compact('user', 'loginSessions'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Current password is incorrect');
                }
            }],
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    /**
     * Show notification settings
     */
    public function notifications()
    {
        $user = Auth::user();
        
        // Get notification preferences from user_settings
        $settings = $user->settings ?? UserSettings::create(['user_id' => $user->id]);
        
        $notificationSettings = [
            'email_trade_alerts' => $settings->email_trade_alerts,
            'email_weekly_report' => $settings->email_weekly_report,
            'email_payment_reminders' => $settings->email_payment_reminders,
            'email_system_updates' => $settings->email_system_updates,
            'push_trade_alerts' => $settings->push_trade_alerts,
            'push_payment_reminders' => $settings->push_payment_reminders,
            'notification_frequency' => $settings->notification_frequency,
        ];

        return view('users.settings.notifications', compact('user', 'notificationSettings'));
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email_trade_alerts' => 'in:0,1',
            'email_weekly_report' => 'in:0,1',
            'email_payment_reminders' => 'in:0,1',
            'email_system_updates' => 'in:0,1',
            'push_trade_alerts' => 'in:0,1',
            'push_payment_reminders' => 'in:0,1',
            'notification_frequency' => 'in:immediate,daily,weekly',
        ]);

        // Convert string values to booleans
        $validated['email_trade_alerts'] = (bool) $validated['email_trade_alerts'];
        $validated['email_weekly_report'] = (bool) $validated['email_weekly_report'];
        $validated['email_payment_reminders'] = (bool) $validated['email_payment_reminders'];
        $validated['email_system_updates'] = (bool) $validated['email_system_updates'];
        $validated['push_trade_alerts'] = (bool) $validated['push_trade_alerts'];
        $validated['push_payment_reminders'] = (bool) $validated['push_payment_reminders'];

        // Get or create settings
        $settings = $user->settings ?? new UserSettings();
        $settings->user_id = $user->id;
        $settings->fill($validated);
        $settings->save();
        
        return redirect()->back()->with('success', 'Notification settings updated successfully');
    }

    /**
     * Show privacy & preferences
     */
    public function preferences()
    {
        $user = Auth::user();
        
        // Get settings from user_settings
        $settings = $user->settings ?? UserSettings::create(['user_id' => $user->id]);
        
        return view('users.settings.preferences', compact('user', 'settings'));
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'theme' => 'in:light,dark,auto',
            'language' => 'in:en,fr,es,de',
            'two_factor_enabled' => 'boolean',
            'profile_visibility' => 'in:public,private,friends',
            'chart_type' => 'in:candlestick,line,bar',
            'timeframe' => 'in:1m,5m,15m,1h,4h,1d',
            'email_marketing' => 'in:0,1',
            'email_newsletter' => 'in:0,1',
        ]);

        // Convert string values to boolean for email preferences
        $validated['email_marketing'] = (bool) $validated['email_marketing'];
        $validated['email_newsletter'] = (bool) $validated['email_newsletter'];

        // Get or create settings
        $settings = $user->settings ?? new UserSettings();
        $settings->user_id = $user->id;
        $settings->fill($validated);
        $settings->save();
        
        return redirect()->back()->with('success', 'Preferences updated successfully');
    }

    /**
     * Logout from all other sessions
     */
    public function logoutOtherSessions(Request $request)
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return redirect()->back()->with('success', 'All other sessions have been logged out');
    }

    /**
     * Show data & privacy page
     */
    public function dataPrivacy()
    {
        $user = Auth::user();
        
        return view('users.settings.data-privacy', compact('user'));
    }

    /**
     * Download user data as ZIP
     */
    public function downloadData(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Create a temporary directory for the export
        $exportDir = storage_path('app/exports/' . $user->id . '_' . time());
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        try {
            // 1. User Profile Data
            $profileData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'country' => $user->country,
                    'city' => $user->city,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ];
            file_put_contents($exportDir . '/profile.json', json_encode($profileData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            // 2. Settings Data
            $settings = $user->settings;
            if ($settings) {
                file_put_contents($exportDir . '/settings.json', json_encode($settings->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }

            // 3. Trading Accounts Data
            $accounts = Account::where('user_id', $userId)->get();
            if ($accounts->count() > 0) {
                file_put_contents($exportDir . '/trading_accounts.json', json_encode($accounts->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }

            // 4. Subscription Data
            $subscriptions = UserSubscription::where('user_id', $userId)->get();
            if ($subscriptions->count() > 0) {
                file_put_contents($exportDir . '/subscriptions.json', json_encode($subscriptions->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }

            // 5. Payment History
            $payments = PaymentTransaction::where('user_id', $userId)->get();
            if ($payments->count() > 0) {
                file_put_contents($exportDir . '/payments.json', json_encode($payments->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }

            // 6. Create ZIP file
            $zipPath = storage_path('app/exports/user_data_' . $user->id . '_' . time() . '.zip');
            $zip = new ZipArchive();
            
            if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
                // Add all files from export directory to zip
                $files = scandir($exportDir);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $zip->addFile($exportDir . '/' . $file, $file);
                    }
                }
                $zip->close();

                // Remove temporary directory
                array_map('unlink', glob($exportDir . '/*'));
                rmdir($exportDir);

                // Send the ZIP file for download
                return response()->download($zipPath, 'user_data_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.zip')->deleteFileAfterSend(true);
            }

            throw new \Exception('Failed to create ZIP file');

        } catch (\Exception $e) {
            // Cleanup on error
            if (is_dir($exportDir)) {
                array_map('unlink', glob($exportDir . '/*'));
                rmdir($exportDir);
            }

            return redirect()->back()->with('error', 'Failed to export data: ' . $e->getMessage());
        }
    }

    /**
     * Delete account (soft delete)
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Validate email confirmation
        $request->validate([
            'email' => 'required|email',
        ]);

        if ($request->email !== $user->email) {
            return redirect()->back()->with('error', 'Email does not match');
        }

        try {
            DB::beginTransaction();

            // 1. Close all active trades if applicable
            // DB::table('trades')->where('user_id', $userId)->where('status', 'active')->update(['status' => 'closed', 'closed_at' => now()]);

            // 2. Cancel all subscriptions
            UserSubscription::where('user_id', $userId)->update(['status' => 'cancelled', 'updated_at' => now()]);

            // 3. Disconnect all trading accounts
            Account::where('user_id', $userId)->update(['is_connected' => false, 'updated_at' => now()]);

            // 4. Delete user settings
            UserSettings::where('user_id', $userId)->delete();

            // 5. Soft delete sessions
            DB::table('sessions')->where('user_id', $userId)->delete();

            // 6. Soft delete the user account (requires deleted_at column)
            $user->delete();

            DB::commit();

            // 7. Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Your account has been successfully deleted. You have been logged out.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete account: ' . $e->getMessage());
        }
    }
}


