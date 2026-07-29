<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Payment\SadadPaymentController;
use Carbon\Carbon;

use App\Mail\OtpEmail;
use App\Models\User;
use App\Models\Otp;
use App\Models\Plan;
use App\Models\PlanUser;
use App\Models\PlanPaymentDetails;
use App\Models\ThemeOption;
use App\Models\StorePage;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

        public function privacyPolicy()
    {
        return view('auth.privacyPolicy');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        Log::info('Admin login attempt', [
            'email' => $credentials['email'],
            'ip'    => $request->ip(),
            'time'  => now()->toDateTimeString(),
        ]);
    
        if (Auth::guard('admin')->attempt($credentials, true)) {
    
            Log::info('Admin login SUCCESS', [
                'email' => $credentials['email'],
                'admin_id' => Auth::guard('admin')->id(),
            ]);
    
            if (!empty($_GET['url'])) {
                return redirect()->intended($_GET['url']);
            }
            return redirect()->intended('/dashboard');
        }
    
        Log::warning('Admin login FAILED', [
            'email'  => $credentials['email'],
            'ip'     => $request->ip(),
            'reason' => 'Invalid credentials',
        ]);
    
        return back()->withErrors([
            'error'    => 'Invalid login credentials',
            'password' => 'Invalid login credentials'
        ]);
    }

    public function showProfile()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.profile.index',compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('admin')->user();

        // Validate the input fields
        // Verify the current password

        // Update the email if it has changed
        if ($request->name !== $user->name) {
            $user->name = $request->name;
        }
        if ($request->phone !== $user->phone) {
            $user->phone = $request->phone;
        }

        // Update password if a new one is provided and confirmed
        if ($request->filled('new_password')) {
                    if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

            $user->password = Hash::make($request->new_password);
        }

        // Handle profile image upload if a new file is provided
        if ($request->hasFile('image')) {
            $fileExtension = strtolower($request->file('image')->getClientOriginalExtension());
            $fileName = time() . '.' . $fileExtension;
            $request->file('image')->move(public_path("uploads/profile"), $fileName);
            $user->image = "uploads/profile/" . $fileName;
        }

        // Update other profile information

        $user->save();

        return redirect()->route('admin.user.profileShow')->with('successMsg', 'تم تحديث المعلومات بنجاح');
    }


    public function showDashboard()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/');
        }

        $user = Auth::guard('admin')->user();

        // Get statistics
        $totalOrders = \App\Models\Orders::count();
        $totalSales = \App\Models\Orders::sum('total_net_a_pay');
        $dailySales = \App\Models\Orders::whereDate('created_at', today())->count();
        $dailyProfits = \App\Models\Orders::whereDate('created_at', today())->sum('total_net_a_pay');

        // Get monthly statistics for charts
        $stats = $this->getMonthlyStats();

        return view('admin.dashboard-fr', compact('user', 'totalOrders', 'totalSales', 'dailySales', 'dailyProfits', 'stats'));
    }

    private function getMonthlyStats()
    {
        $months = [];
        $monthlyOrders = [];
        $monthlyProfits = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $ordersCount = \App\Models\Orders::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyOrders[] = $ordersCount;

            $profitsSum = \App\Models\Orders::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_net_a_pay');
            $monthlyProfits[] = round($profitsSum, 2);
        }

        // Recent statistics (completed, in progress, cancelled)
        $completed = \App\Models\Orders::where('status', 5)->count();
        $inProgress = \App\Models\Orders::where('status', 2)->count();
        $cancelled = \App\Models\Orders::where('status', 4)->count();

        return [
            'months' => $months,
            'monthlyOrders' => $monthlyOrders,
            'monthlyProfits' => $monthlyProfits,
            'recentStatistics' => [$completed, $inProgress, $cancelled]
        ];
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('/');
    }
}
