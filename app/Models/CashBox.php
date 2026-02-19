<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashBox extends Model
{
    use SoftDeletes;

    protected $table = 'cash_boxes';

    protected $fillable = ['name'];

    public $timestamps = false;
}
