<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'citygroup';

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'group',
        'city',
    ];
}
