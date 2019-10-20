<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryDns extends Model
{
    protected $fillable = ['record_id', 'old_ip','new_ip'];

    public function record()
    {
        return $this->hasOne(RecordList::class, 'id', 'record_id');
    }
    //    public function domain()
    //    {
    //        return $this->record->domain();
    //    }
}
