<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    //
    use Sortable,
        SoftDeletes;

    public $sortable = ['ruc', 'name', 'email', 'created_at', 'updated_at'];
}
