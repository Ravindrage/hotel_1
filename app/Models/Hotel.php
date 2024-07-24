<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'city', 'state', 'country', 'fax', 'description', 
        'zip_code', 'phone', 'email', 'website', 'hotel_url', 'contact_person', 
        'faxnumber', 'hotel_contact_email', 'hotel_accounting_emails', 'authorised_by', 
        'hotel_telephone', 'hotel_fax_number', 'main_hotel_image', 'hotel_logo', 
        'username', 'password', 'destination_keywords', 'avg_room_rate', 'tax_rate', 
        'cancellation_days', 'special_offers', 'no_of_rooms', 'booking_days', 
        'auto_send_arrival_report', 'is_asi_hotel'
    ];


    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities');
    }

    public function policies()
    {
        return $this->hasOne(HotelPolicy::class);
    }

    public function attractions()
    {
        return $this->hasMany(HotelAttraction::class);
    }
    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }
}

