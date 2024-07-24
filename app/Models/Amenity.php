<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_amenities');
    }


    public function parent()
    {
        return $this->belongsTo(Amenity::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Amenity::class, 'parent_id');
    }
}

