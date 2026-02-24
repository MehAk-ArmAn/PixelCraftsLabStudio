<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Particle extends Model
{
    protected $fillable = ['image_path', 'active']; // allow mass assignment
}
