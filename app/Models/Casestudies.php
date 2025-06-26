<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Casestudies extends Model
{
     use HasFactory;
     protected $fillable = [
        'title',
        'role',
        'goal',
        'wireframe_image',
        'mockup_image',
        'prototype_image',
        'final_design_image',
    ];
}
