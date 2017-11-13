<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyUser extends Model
{
    use Sortable,
        SoftDeletes;

    public $sortable = ['company_id', 'user_id', 'active'];

    protected $table = 'company_users';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id');
    }
}
