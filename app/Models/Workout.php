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
];


}
