<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'movies';

    protected $fillable = [
        'title',
        'summary',
        'poster',
        'genre_id',
        'year'
    ];

    public function genre()
    {
        return $this->belongsTo(Genres::class, 'genre_id');
    }

    public function listReviews()
    {
        return $this->hasMany(Reviews::class, 'movie_id');
    }

    public function listCasts()
    {
        return $this->belongsToMany(Casts::class, 'casts__movies', 'movie_id', 'cast_id');
    }

    public function castmovie()
    {
        return $this->hasOne(Casts_Movie::class, 'movie_id');
    }
}
