<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static getAdvertisementPublished()
 * @method static getAdvertisementsAll(string $string, string $string1)
 * @method static getAdvertisementModel($department_id)
 */
class Advertisement extends Model
{
    protected $table = "advertisements";
    protected $fillable = [
        'title',
        'code',
        'parent_code',
        'department_id',
        'publicationDate',
        'eventDate',
        'fragment',
        'description',
        'published',
        'image_id',
        'time',
        'place',
        'guest'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    // Scopes
    public function scopeGetAdvertisementMold($query, $department_id, $orderBy, $orderByValue)
    {
        return $query->with(array('department' => function($q) {
            $q->select('id', 'name');
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->whereIn('department_id', $department_id)
           ->orderBy($orderBy, $orderByValue)->get();
    }
    public function scopeGetAdvertisementsAll($query, $orderBy, $orderByValue)
    {
        return $query->with(array('department' => function($q) {
            $q->select('id', 'name');
        }))->with(array('image' => function($q) {
            $q->select('id', 'path');
        }))->orderBy($orderBy, $orderByValue)->get();
    }

    public function scopeGetAdvertisementPublished($query)
    {
        return $query->where('published', 1);
    }
}
