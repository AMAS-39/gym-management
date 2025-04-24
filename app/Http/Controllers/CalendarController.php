<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function events()
    {
        $user = Auth::user();

        $query = Workout::query();

        if ($user->role === 'trainer') {
            $query->where('trainer_id', $user->id);
        } elseif ($user->role === 'user') {
            $query->where('client_id', $user->id);
        }

        $events = $query->with(['client', 'trainer'])->get()->map(function ($workout) {
            return [
                'title' => $workout->client->name . ' - ' . $workout->trainer->name,
                'start' => $workout->date . 'T' . $workout->start_time,
                'end' => $workout->date . 'T' . $workout->end_time,
                'color' => $this->getTrainerColor($workout->trainer_id), // 🔥 Dynamic color
            ];
        });

        return response()->json($events);
    }

    // 👉 Add this function BELOW your events() method
    private function getTrainerColor($id)
    {
        $colors = [
            1 => '#3b82f6', // Blue
            2 => '#10b981', // Green
            3 => '#f59e0b', // Yellow
            4 => '#ef4444', // Red
            5 => '#8b5cf6', // Violet
        ];

        return $colors[$id] ?? '#6366f1'; // Default Purple
    }
}
