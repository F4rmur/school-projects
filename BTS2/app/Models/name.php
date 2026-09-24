<?php

namespace App\Models;

use Database\Factories\NameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class name extends Model
{
    /** @use HasFactory<NameFactory> */
    use HasFactory;
}
