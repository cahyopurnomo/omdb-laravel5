<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouriteModel extends Model
{
    protected $table = 'favourites';
    protected $fillable = ['user_id','imdb_id','title','year','poster'];
    public $timestamps = true;
}
