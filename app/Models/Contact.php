<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'contact_number',
        'email',
        'description'
    ];
}
