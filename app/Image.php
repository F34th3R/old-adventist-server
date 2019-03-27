<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = "images";
    protected $fillable = ['name', 'tag', 'path'];

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }
}
