<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static getIdAndName(string $string, string $string1)
 */
class Department extends Model
{
    protected $table = "departments";
    protected $fillable = ['name','code', 'user_id'];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    // Scopes
    public function scopeGetIdAndName($query, $orderBy, $value)
    {
        return $query->orderBy($orderBy, $value)->select('id', 'name')->get();
    }
}
