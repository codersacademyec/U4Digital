<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostType extends Model
{
    use Sortable,
        SoftDeletes;

    protected $table = 'post_types';
}
