<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static getChurchId(string $string, $id)
 * @method static getIdAndName(string $string, string $string1)
 */
class Church extends Model
{
    protected $table = "churches";
    protected $fillable = ['name', 'code', 'group_id', 'user_id', 'deleted'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeGetIdAndName($query, $orderBy, $value)
    {
        return $query->orderBy($orderBy, $value)->select('id', 'name')->get();
    }
    public function scopeGetChurchId($query, $equals, $value)
    {
        return $query->where($equals, $value)->select('id')->get();
    }
    public function scopeGetChurchUserId($query, $equals, $value)
    {
        return $query->where($equals, $value)->select('user_id')->get();
    }
}
