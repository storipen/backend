<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoryCategory extends Model
{
    //
    protected $fillable = [
        'idcategory'
    ];
    public function stories(){
        return $this->belongsTo(Story::class,'id');
    }
}
