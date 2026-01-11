<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'start_date',
        'end_date',
        'justification',
        'status',
        'type',
        'validated_by',
        'admin_note'
    ];
    // see if the resource is Available in this period
    public static function isAvailable($resourceId, $start, $end){
        return !self::where('resource_id', $resourceId)
        ->whereIn('status', ['pending', 'approved'])
        ->where(function ($query) use ($start, $end) {
            $query->where('start_date', '<', $end)
            ->where('end_date', '>', $start);
        })->exists();
    }
}
