<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue     = Booking::where('status', 'confirmed')->sum('total_amount');
        $monthlyRevenue   = Booking::where('status', 'confirmed')
                                ->whereMonth('created_at', now()->month)->sum('total_amount');
        $lastMonthRevenue = Booking::where('status', 'confirmed')
                                ->whereMonth('created_at', now()->subMonth()->month)->sum('total_amount');
        $revenueTrend     = $lastMonthRevenue > 0
                                ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
                                : 0;

        $totalBookings     = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $bookingTrend      = Booking::whereMonth('created_at', now()->month)->count()
                           - Booking::whereMonth('created_at', now()->subMonth()->month)->count();

        $totalUsers  = User::where('is_admin', false)->count();
        $totalHotels = Hotel::count();

        $recentBookings = Booking::with(['hotel', 'room', 'user'])->latest()->take(10)->get();

        $bookingsByStatus = Booking::select('status', DB::raw('count(*) as count'))
                                ->groupBy('status')->get()->pluck('count', 'status');

        $topHotels = Hotel::withCount('bookings')->orderBy('bookings_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'monthlyRevenue', 'revenueTrend',
            'totalBookings', 'confirmedBookings', 'bookingTrend',
            'totalUsers', 'totalHotels', 'recentBookings',
            'bookingsByStatus', 'topHotels'
        ));
    }
}