<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static getUnionId($id)
 * @method static getUnionUserId($id)
 * @method static getIdAndName(string $string, string $string1)
 */
class Union extends Model
{
    protected $table = "unions";
    protected $fillable = ['name', 'code', 'user_id', 'deleted'];

    public function groups()
    {
        return $this->hasMany(Group::class);
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

    public function scopeGetUnionId($query, $id)
    {
        return $query->where('id', $id)->select('id')->get();
    }

    public function scopeGetUnionUserId($query, $id)
    {
        return $query->where('id', $id)->select('user_id')->get();
    }
}
