<?php
// app/Models/Gallery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        // Hero Section - Original fields
        'title',
        'subtitle',
        'description',
        
        // Featured Images (3) - Original fields
        'image1',
        'image1_title',
        'image2',
        'image2_title',
        'image3',
        'image3_title',
        
        // Gallery Images (9) - Original fields
        'gallery_img1',
        'gallery_img1_title',
        'gallery_img2',
        'gallery_img2_title',
        'gallery_img3',
        'gallery_img3_title',
        'gallery_img4',
        'gallery_img4_title',
        'gallery_img5',
        'gallery_img5_title',
        'gallery_img6',
        'gallery_img6_title',
        'gallery_img7',
        'gallery_img7_title',
        'gallery_img8',
        'gallery_img8_title',
        'gallery_img9',
        'gallery_img9_title',

        'status'
    ];
}