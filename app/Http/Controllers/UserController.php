<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userlist()
    {
        return view('user.list');
    }

    public function getUserData(Request $request)
    {
        extract($this->DTFilters($request->all()));

        $filterData = $request->data;

        $activityLogFilter = function ($query) use ($filterData) {
            if ($filterData == 1) {
                $query->whereDate('created_at', today()); // Filter today's data
            } elseif ($filterData == 2) {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year); // Filter current month’s data
            } elseif ($filterData == 3) {
                $query->whereYear('created_at', now()->year); // Filter current year’s data
            }
        };

        $searchedUsers = User::withSum(['activitylog' => $activityLogFilter], 'points')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', $search);
            })
            ->orderBy($sort_column, $sort_order)
            ->get();

        $remainingLimit = $limit - $searchedUsers->count();

        $otherUsers = User::withSum(['activitylog' => $activityLogFilter], 'points')
            ->whereNotIn('id', $searchedUsers->pluck('id')->toArray())
            ->orderBy($sort_column, $sort_order)
            ->when($remainingLimit > 0, function ($query) use ($offset, $remainingLimit) {
                $query->offset($offset)->limit($remainingLimit);
            })
            ->get();

        $users = $searchedUsers->merge($otherUsers);

        $totalUsersCount = User::withSum(['activitylog' => $activityLogFilter], 'points')->count();
        $filteredUsersCount = $searchedUsers->count() + $otherUsers->count();

        $records = [
            "recordsTotal" => $totalUsersCount,
            "recordsFiltered" => $filteredUsersCount,
            'data' => []
        ];

        foreach ($users as $key => $emp) {
            $records['data'][] = [
                'id' => $emp->id,
                'name' => $emp->name,
                'point' => $emp->activitylog_sum_points ?? 0,
                'rank' => $emp->rank,
            ];
        }

        return $records;
    }

    public function recalculate()
    {
        $users = User::withSum('activitylog', 'points')
            ->orderByDesc('activitylog_sum_points')
            ->get();

        // dd($users);

        $rank = 0; // Initial rank
        $previousPoints = null; // Track previous user's points

        foreach ($users as $index => $user) {
            $totalPoints = $user->activitylog_sum_points ?? 0;

            if ($totalPoints !== $previousPoints) {
                $rank = $rank + 1;
            }

            User::where('id', $user->id)->update(['rank' => $rank]);

            $previousPoints = $totalPoints;
        }

        return redirect()->back();
    }
}
