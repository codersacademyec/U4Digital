<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use Sortable,
        SoftDeletes;

    protected $table = 'calendar_events';

    public function company()
    {
        return $this->belongsTo('App\Models\Company','company_id','id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\CalendarComment','calendar_events_id', 'id');
    }

    public function typePost()
    {
        return $this->belongsTo('App\Models\PostType','post_types_id','id');
    }
}

