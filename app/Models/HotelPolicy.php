<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPolicy extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id', 'check_in', 'check_out', 'cancellation_pre_payment', 
        'children_extra_beds', 'pets', 'cards_accepted', 'resort_fee'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
