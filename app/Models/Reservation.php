<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';
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
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    // see if the resource is Available in this period
    public static function isAvailable($resourceId, $start, $end, $ignoreId = null)
    {
        return !self::where('resource_id', $resourceId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($ignoreId) {
                if ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                }
            })
            ->where(function ($query) use ($start, $end) {
                $query->where('start_date', '<', $end)
                      ->where('end_date', '>', $start);
            })
            ->exists();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
