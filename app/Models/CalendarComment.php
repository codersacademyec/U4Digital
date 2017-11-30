<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarComment extends Model
{
    use Sortable,
        SoftDeletes;

    protected $table = 'calendar_event_comments';

    public function calendarEvent()
    {
        return $this->belongsTo('App\Models\CalendarEvent','calendar_events_id','id');
    }
}

