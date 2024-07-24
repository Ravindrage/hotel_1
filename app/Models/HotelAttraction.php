<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAttraction extends Model
{
    use HasFactory;
    protected $fillable = ['hotel_id', 'type', 'name', 'distance'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
