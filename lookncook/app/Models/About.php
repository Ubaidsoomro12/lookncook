<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'abouts';

    protected $fillable = [
        'title',
        'subtitle',
        'subdescription',
        'description',
        'image1',
        'image2',
        'image3',
    ];
}