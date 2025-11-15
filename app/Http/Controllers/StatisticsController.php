<?php

namespace App\Http\Controllers;

use App\Models\PersonalTask;
use App\Models\TeamTask;
use App\Models\TeamMember; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function monthly(Request $request)
    {
        $user = Auth::user();

        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();

        $userMemberIds = TeamMember::where('user_id', $user->id)->pluck('id');

        $months = [];
        $labels = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            if ($current->year != $startDate->year) {
                break;
            }

            $months[] = [
                'start' => $current->copy()->startOfMonth(),
                'end'   => $current->copy()->endOfMonth(),
                'label' => $current->format('M')
            ];
            $labels[] = $current->format('M');
            $current->addMonth();
        }

        $personalCompleted = [];
        $personalPending = [];
        $teamCompleted = [];
        $teamPending = [];

        foreach ($months as $m) {
            $pCompleted = PersonalTask::where('user_id', $user->id)
                ->where('completed', true)
                ->whereBetween('updated_at', [$m['start'], $m['end']])
                ->count();
            
            $pPending = PersonalTask::where('user_id', $user->id)
                ->where('completed', false)
                ->whereBetween('due_date', [$m['start'], $m['end']])
                ->count();

            $personalCompleted[] = $pCompleted;
            $personalPending[] = $pPending;

            $tCompleted = TeamTask::whereIn('member_id', $userMemberIds)
                ->where('is_completed', true)
                ->whereBetween('updated_at', [$m['start'], $m['end']])
                ->count();

            $tPending = TeamTask::whereIn('member_id', $userMemberIds)
                ->where('is_completed', false)
                ->whereBetween('deadline', [$m['start'], $m['end']])
                ->count();

            $teamCompleted[] = $tCompleted;
            $teamPending[] = $tPending;
        }

        $response = [
            'labels' => $labels,
            'personal' => [
                'completed' => $personalCompleted,
                'pending' => $personalPending,
                'total' => array_sum($personalCompleted) + array_sum($personalPending),
            ],
            'team' => [
                'completed' => $teamCompleted,
                'pending' => $teamPending,
                'total' => array_sum($teamCompleted) + array_sum($teamPending),
            ],
        ];

        $response['all'] = [
            'completed' => array_map(function ($a, $b) { return $a + $b; }, $response['personal']['completed'], $response['team']['completed']),
            'pending'   => array_map(function ($a, $b) { return $a + $b; }, $response['personal']['pending'], $response['team']['pending']),
        ];
        $response['totals'] = [
            'personal' => $response['personal']['total'],
            'team' => $response['team']['total'],
            'all' => $response['personal']['total'] + $response['team']['total'],
        ];

        return response()->json($response);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : now()->subDays(30)->startOfDay();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : now()->endOfDay();
            
        $currentType = $request->input('type', 'personal');
            
        if ($endDate->lt($startDate)) {
            $endDate = $startDate->copy()->endOfDay();
        }
        
        if ($currentType == 'team') {
            $userMemberIds = TeamMember::where('user_id', $user->id)->pluck('id');
            $tasks = TeamTask::whereIn('member_id', $userMemberIds) 
                             ->whereBetween('deadline', [$startDate, $endDate])
                             ->get();
        } else {
            $tasks = PersonalTask::where('user_id', $user->id)
                             ->whereBetween('due_date', [$startDate, $endDate])
                             ->get();
        }
        
        $totalTasks = $tasks->count();
        if ($currentType == 'team') {
            $completedTasks = $tasks->where('is_completed', true)->count();
        } else {
            $completedTasks = $tasks->where('completed', true)->count();
        }
        $pendingTasks = $totalTasks - $completedTasks;
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $currentStreak = $this->calculateCurrentStreak($user->id, $startDate, $endDate, $currentType);
        $dailyData = $this->getDailyCompletionData($user->id, $startDate, $endDate, $currentType);
        $priorityData = $this->getPriorityDistribution($user->id, $startDate, $endDate, $currentType);
        
        return view('todo.statistics', [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'completionRate' => $completionRate,
            'currentStreak' => $currentStreak,
            'dailyData' => $dailyData,
            'priorityData' => $priorityData,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'currentType' => $currentType,
        ]);
    }
    
    private function calculateCurrentStreak($userId, $startDate, $endDate, $type)
    {
        if ($type == 'team') {
            $userMemberIds = TeamMember::where('user_id', $userId)->pluck('id');
            $query = TeamTask::whereIn('member_id', $userMemberIds)->where('is_completed', true);
        } else {
            $query = PersonalTask::where('user_id', $userId)->where('completed', true);
        }

        $completedTasks = $query->whereBetween('updated_at', [$startDate, $endDate])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $streak = 0;
        $previousDate = null;
        $today = $endDate->copy()->endOfDay();
        $startDate = $startDate->copy()->startOfDay();
        
        foreach ($completedTasks as $task) {
            $completedDate = Carbon::parse($task->updated_at)->startOfDay();
            
            if ($completedDate < $startDate || $completedDate > $today) {
                continue;
            }
            
            if ($previousDate === null) {
                $streak = 1;
                $previousDate = $completedDate;
            } else {
                $daysDifference = $previousDate->diffInDays($completedDate);
                
                if ($daysDifference === 0) {
                    continue;
                } else if ($daysDifference === 1) {
                    $streak++;
                    $previousDate = $completedDate;
                } else {
                    break;
                }
            }
        }
        
        return $streak;
    }
    
    private function getDailyCompletionData($userId, $startDate, $endDate, $type)
    {
        $days = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $days[] = [
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->format('M d'),
                'start' => $currentDate->copy()->startOfDay(),
                'end' => $currentDate->copy()->endOfDay(),
                'isCurrent' => $currentDate->isToday()
            ];
            $currentDate->addDay();
        }
        
        if ($type == 'team') {
            $userMemberIds = TeamMember::where('user_id', $userId)->pluck('id');
            $tasks = TeamTask::whereIn('member_id', $userMemberIds)
                             ->whereBetween('deadline', [$startDate, $endDate])
                             ->get();
        } else {
            $tasks = PersonalTask::where('user_id', $userId)
                             ->whereBetween('due_date', [$startDate, $endDate])
                             ->get();
        }
        
        $dailyData = [];
        
        foreach ($days as $day) {
            $dateColumn = ($type == 'team') ? 'deadline' : 'due_date';

            $dayTasks = $tasks->filter(function($task) use ($day, $dateColumn) {
                $taskDate = Carbon::parse($task->{$dateColumn});
                return $taskDate->between($day['start'], $day['end']);
            });
            
            if ($type == 'team') {
                $completed = $dayTasks->where('is_completed', true)->count();
                $pending = $dayTasks->where('is_completed', false)->count();
            } else {
                $completed = $dayTasks->where('completed', true)->count();
                $pending = $dayTasks->where('completed', false)->count();
            }
            
            $dailyData[] = [
                'date' => $day['date'],
                'label' => $day['label'],
                'completed' => $completed,
                'pending' => $pending,
                'total' => $completed + $pending,
                'isCurrent' => $day['isCurrent']
            ];
        }
        
        return $dailyData;
    }

    private function getPriorityDistribution($userId, $startDate, $endDate, $type)
    {
        if ($type == 'team') {
            $userMemberIds = TeamMember::where('user_id', $userId)->pluck('id');
            $query = TeamTask::whereIn('member_id', $userMemberIds)
                             ->whereBetween('deadline', [$startDate, $endDate]);
        } else {
            $query = PersonalTask::where('user_id', $userId)
                             ->whereBetween('due_date', [$startDate, $endDate]);
        }

        $tasks = $query->select(DB::raw('LOWER(priority) as priority_low'), DB::raw('count(*) as count'))
            ->groupBy('priority_low')
            ->get()
            ->keyBy('priority_low');
        
        $priorities = [
            'low' => 0,
            'medium' => 0,
            'high' => 0
        ];
        
        foreach ($tasks as $priority => $data) {
            if (array_key_exists($priority, $priorities)) {
                $priorities[$priority] = $data->count;
            }
        }
        
        return $priorities;
    }
}