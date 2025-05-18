<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutDetail extends Model
{
    use HasFactory;

    protected $fillable = ['workout_id', 'category_id', 'type_id'];

    public function category()
{
    return $this->belongsTo(\App\Models\WorkoutCategory::class);
}

public function type()
{
    return $this->belongsTo(\App\Models\WorkoutType::class);
}

}
