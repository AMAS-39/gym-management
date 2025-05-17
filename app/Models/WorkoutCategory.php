<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function types() {
        return $this->hasMany(WorkoutType::class);
    }




}

