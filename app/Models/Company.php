<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Company extends Model
{
    //
    use Sortable;

    public $sortable = ['ruc', 'name', 'email', 'created_at', 'updated_at'];
}
