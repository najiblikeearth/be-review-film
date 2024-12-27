<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Casts extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'casts';

    protected $fillable = [
        'name',
        'age',
        'bio'
    ];

    public function castmovie()
    {
        return $this->hasOne(Casts_Movie::class, 'cast_id');
    }
}
