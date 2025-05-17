<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'workout_category_id'];


    public function category() {
        return $this->belongsTo(WorkoutCategory::class, 'workout_category_id');
    }

}
