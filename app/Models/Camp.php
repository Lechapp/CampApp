<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'available_seats',
        'price',
        'start_date',
        'end_date'
    ];

    public function participants(){
        return $this->hasMany(Participant::class);
    }

}
