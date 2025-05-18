<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutCategory;
use App\Models\WorkoutDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkoutController extends Controller
{
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $clients = User::where('role', 'user')->get();
        $categories = WorkoutCategory::with('types')->get();

        return view('workouts.create', compact('clients', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'     => 'required|exists:users,id',
            'dates'         => 'required|array|min:1',
            'dates.*'       => 'required|date',
            'times'         => 'required|array|min:1',
            'times.*'       => 'required|date_format:H:i',
            'category_ids'  => 'required|array',
            'type_ids'      => 'required|array',
            'description'   => 'required|string|max:1000',
        ]);

        $clientId    = $request->client_id;
        $dates       = $request->dates;
        $times       = $request->times;
        $description = $request->description;
        $repeat      = $request->has('repeat') ? 4 : 1;

        foreach ($dates as $i => $dateString) {
            $start = Carbon::parse($times[$i]);
            $end   = $start->copy()->addHour();

            for ($week = 0; $week < $repeat; $week++) {
                $date = Carbon::parse($dateString)->addWeeks($week)->toDateString();

                // Overlap check
                $overlap = Workout::where('client_id', $clientId)
                    ->where('date', $date)
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_time', [$start, $end])
                              ->orWhereBetween('end_time', [$start, $end])
                              ->orWhere(function ($q) use ($start, $end) {
                                  $q->where('start_time', '<=', $start)
                                    ->where('end_time', '>=', $end);
                              });
                    })->exists();

                if ($overlap) {
                    return back()->withErrors([
                        'error' => "⛔ Overlap detected on {$date} at {$start->format('H:i')} — workout not scheduled."
                    ]);
                }

                // Store workout
                $workout = Workout::create([
                    'trainer_id'  => auth()->id(),
                    'client_id'   => $clientId,
                    'date'        => $date,
                    'start_time'  => $start,
                    'end_time'    => $end,
                    'description' => $description,
                ]);

                // Store workout details (category/type pairs)
                $categorySet = $request->input("category_ids.$i", []);
                $typeSet     = $request->input("type_ids.$i", []);

                foreach ($categorySet as $key => $catId) {
                    $typeId = $typeSet[$key] ?? null;
                    if ($catId && $typeId) {
                        WorkoutDetail::create([
                            'workout_id'  => $workout->id,
                            'category_id' => $catId,
                            'type_id'     => $typeId,
                        ]);
                    }
                }
            }
        }

        return back()->with('success', '✅ Workout(s) scheduled successfully' . ($repeat > 1 ? ' and repeated for 4 weeks.' : '.'));
    }
}
