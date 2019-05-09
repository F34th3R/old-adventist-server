<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static getGroupUserId(string $string, $id)
 * @method static getGroupId(string $string, $id)
 * @method static getIdAndName(string $string, string $string1)
 */
class Group extends Model
{
    protected $table = "groups";
    protected $fillable = ['name', 'code', 'union_id', 'user_id', 'deleted'];

    public function union() {
        return $this->belongsTo(Union::class);
    }

    public function churches()
    {
        return $this->hasMany(Church::class);
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
    public function scopeGetGroupId($query, $equals, $value)
    {
        return $query->where($equals, $value)->select('id')->get();
    }
    public function scopeGetGroupUserId($query, $equals, $value)
    {
        return $query->where($equals, $value)->select('user_id')->get();
    }

}
