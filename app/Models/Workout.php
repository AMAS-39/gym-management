<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    public function trainer()
{
    return $this->belongsTo(User::class, 'trainer_id');
}

public function client()
{
    return $this->belongsTo(User::class, 'client_id');
}
protected $fillable = [
    'trainer_id',
    'client_id',
    'date',
    'start_time',
    'end_time',
    'description',
    'category_id',
    'type_id'
];

public function category()
{
    return $this->belongsTo(\App\Models\WorkoutCategory::class);
}

public function type()
{
    return $this->belongsTo(\App\Models\WorkoutType::class);
}



}
