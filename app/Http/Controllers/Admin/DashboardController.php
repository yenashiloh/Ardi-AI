<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Response;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;

class DashboardController extends Controller
{
    /**
     * Show Dashboard Page with analytics data
     */
    public function showDashboardPage()
    {
        $user = Auth::user();
        
        // Get dashboard stats
        $totalUsers = $this->getTotalUsers();
        $activeUsers = $this->getActiveUsers();
        $dailyActiveUsers = $this->getDailyActiveUsers();
        $messagesExchanged = $this->getMessagesExchanged();
        
        // Get data for user growth chart
        $userGrowthData = $this->getUserGrowthData();
        
        // Get data for message activity chart
        $messageActivityData = $this->getMessageActivityData();
        
        // Get top queries
        $topQueries = $this->getTopQueries();
        
        return view('admin.dashboard.dashboard', [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->role,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'dailyActiveUsers' => $dailyActiveUsers,
            'messagesExchanged' => $messagesExchanged,
            'userGrowthData' => json_encode($userGrowthData),
            'messageActivityData' => json_encode($messageActivityData),
            'topQueries' => $topQueries
        ]);
    }
    
    /**
     * Get total number of users
     */
    private function getTotalUsers()
    {
        return User::count();
    }
    
    /**
     * Get number of active users (non-archived users)
     */

    private function getActiveUsers()
    {
        return User::where('status', 'Active')
                ->where('is_archive', 0)
                ->count();
    }
    
    /**
     * Get number of users active in the last 24 hours
     * Note: This is a 'last_login_at' field in User model
     */
    private function getDailyActiveUsers()
    {
        $yesterday = Carbon::now()->subDay();
        $yesterdayUTC = new UTCDateTime($yesterday->timestamp * 1000);
        
        return User::where('last_login_at', '>=', $yesterdayUTC)->count();
    }
    
    /**
     * Get total number of messages/responses
     */
    private function getMessagesExchanged()
    {
        return Response::count();
    }
    
    /**
     * Get user growth data for the last 12 months
     */
    private function getUserGrowthData()
    {
        $data = [];
        $now = Carbon::now();
        
        // Loop through the last 12 months
        for ($i = 0; $i < 12; $i++) {
            $month = $now->copy()->subMonths($i);
            $monthStart = $month->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthStartUTC = new UTCDateTime($monthStart->timestamp * 1000);
            $monthEndUTC = new UTCDateTime($monthEnd->timestamp * 1000);
            
            // Count users created in this month
            $count = User::where('created_at', '>=', $monthStartUTC)
                         ->where('created_at', '<=', $monthEndUTC)
                         ->count();
            
            // Add to data array (in reverse order so newest month is last)
            $data[11 - $i] = [
                'month' => $monthStart->format('M Y'),
                'users' => $count
            ];
        }
        
        return array_values($data);
    }
    
    /**
     * Get message activity data for the last 7 days
     */
    private function getMessageActivityData()
    {
        $data = [];
        $now = Carbon::now();
        
        // Loop through the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $day = $now->copy()->subDays($i);
            $dayStart = $day->startOfDay();
            $dayEnd = $day->copy()->endOfDay();
            
            $dayStartUTC = new UTCDateTime($dayStart->timestamp * 1000);
            $dayEndUTC = new UTCDateTime($dayEnd->timestamp * 1000);
            
            // Count responses created in this day
            $count = Response::where('created_at', '>=', $dayStartUTC)
                            ->where('created_at', '<=', $dayEndUTC)
                            ->count();
            
            // Add to data array (in reverse order so today is last)
            $data[6 - $i] = [
                'day' => $dayStart->format('D'),
                'messages' => $count
            ];
        }
        
        return array_values($data);
    }
    
    /**
     * Get top 5 most frequent queries
     */
    private function getTopQueries()
    {
        // Perform an aggregation to group and count questions
        $topQueries = Response::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$question',
                        'count' => ['$sum' => 1]
                    ]
                ],
                [
                    '$sort' => ['count' => -1]
                ],
                [
                    '$limit' => 5
                ]
            ])->toArray();
        });
        
        // Format the result
        $result = [];
        foreach ($topQueries as $query) {
            $result[] = [
                'question' => $query->_id,
                'count' => $query->count
            ];
        }
        
        return $result;
    }
    
    /**
     * Get API data for dashboard widgets
     */
    public function getDashboardStats()
    {
        return response()->json([
            'totalUsers' => $this->getTotalUsers(),
            'activeUsers' => $this->getActiveUsers(),
            'dailyActiveUsers' => $this->getDailyActiveUsers(),
            'messagesExchanged' => $this->getMessagesExchanged(),
            'userGrowthData' => $this->getUserGrowthData(),
            'messageActivityData' => $this->getMessageActivityData()
        ]);
    }

    /**
     * Export dashboard data as CSV or PDF
     */
    public function exportDashboardReport(Request $request)
    {
        $format = $request->query('format', 'csv');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Default to last 30 days if dates not provided
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        }
        
        if (!$endDate) {
            $endDate = Carbon::now()->format('Y-m-d');
        }
        
        // Convert to Carbon instances
        $startDateCarbon = Carbon::parse($startDate)->startOfDay();
        $endDateCarbon = Carbon::parse($endDate)->endOfDay();
        
        // Convert to MongoDB UTC format
        $startDateUTC = new UTCDateTime($startDateCarbon->timestamp * 1000);
        $endDateUTC = new UTCDateTime($endDateCarbon->timestamp * 1000);
        
        // Get data for report
        $data = [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'newUsers' => User::where('created_at', '>=', $startDateUTC)
                              ->where('created_at', '<=', $endDateUTC)
                              ->count(),
            'messages' => Response::where('created_at', '>=', $startDateUTC)
                                 ->where('created_at', '<=', $endDateUTC)
                                 ->count(),
            'period' => $startDateCarbon->format('M d, Y') . ' - ' . $endDateCarbon->format('M d, Y'),
        ];
        
        // Get daily user signups
        $dailySignups = [];
        $currentDate = clone $startDateCarbon;
        
        while ($currentDate <= $endDateCarbon) {
            $nextDate = (clone $currentDate)->addDay();
            
            $dayStartUTC = new UTCDateTime($currentDate->timestamp * 1000);
            $dayEndUTC = new UTCDateTime($nextDate->timestamp * 1000);
            
            $count = User::where('created_at', '>=', $dayStartUTC)
                         ->where('created_at', '<', $dayEndUTC)
                         ->count();
            
            $dailySignups[] = [
                'date' => $currentDate->format('Y-m-d'),
                'count' => $count
            ];
            
            $currentDate->addDay();
        }
        
        $data['dailySignups'] = $dailySignups;
        
        // Get daily messages
        $dailyMessages = [];
        $currentDate = clone $startDateCarbon;
        
        while ($currentDate <= $endDateCarbon) {
            $nextDate = (clone $currentDate)->addDay();
            
            $dayStartUTC = new UTCDateTime($currentDate->timestamp * 1000);
            $dayEndUTC = new UTCDateTime($nextDate->timestamp * 1000);
            
            $count = Response::where('created_at', '>=', $dayStartUTC)
                            ->where('created_at', '<', $dayEndUTC)
                            ->count();
            
            $dailyMessages[] = [
                'date' => $currentDate->format('Y-m-d'),
                'count' => $count
            ];
            
            $currentDate->addDay();
        }
        
        $data['dailyMessages'] = $dailyMessages;
        
        // Export as CSV or PDF based on format
        if ($format === 'csv') {
            return $this->exportCsv($data);
        } else {
            return $this->exportPdf($data);
        }
    }
    
    /**
     * Export dashboard data as CSV
     */
    private function exportCsv($data)
    {
        $filename = 'dashboard_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Summary stats
            fputcsv($file, ['Dashboard Report', $data['period']]);
            fputcsv($file, ['Total Users', $data['totalUsers']]);
            fputcsv($file, ['Active Users', $data['activeUsers']]);
            fputcsv($file, ['New Users (Period)', $data['newUsers']]);
            fputcsv($file, ['Messages (Period)', $data['messages']]);
            fputcsv($file, []);
            
            // Daily signups
            fputcsv($file, ['Daily User Signups']);
            fputcsv($file, ['Date', 'Count']);
            
            foreach ($data['dailySignups'] as $row) {
                fputcsv($file, [$row['date'], $row['count']]);
            }
            
            fputcsv($file, []);
            
            // Daily messages
            fputcsv($file, ['Daily Messages']);
            fputcsv($file, ['Date', 'Count']);
            
            foreach ($data['dailyMessages'] as $row) {
                fputcsv($file, [$row['date'], $row['count']]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export dashboard data as PDF
     * Note: This requires the laravel-dompdf package
     */
    private function exportPdf($data)
    {
        // If using a package like laravel-dompdf
        // return PDF::loadView('admin.dashboard.report', $data)->download('dashboard_report_' . date('Y-m-d') . '.pdf');
        
        // For now, we'll just return a message that PDF export requires additional packages
        return response()->json([
            'success' => false,
            'message' => 'PDF export requires the laravel-dompdf package. Please install it or use CSV export.'
        ]);
    }
    
    /**
     * Logout 
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}