<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Story extends Model
{
    //
    use Sortable;

    
    public $sortable = ['title'];

    
    
    public function storycategory(){
        return $this->hasMany(StoryCategory::Class,'story_id');
    }
    
}
