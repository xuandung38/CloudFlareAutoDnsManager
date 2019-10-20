<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordList extends Model
{
    protected $fillable = ['domain_id', 'record', 'old_ip', 'new_ip', 'id_record'];

    public function domain()
    {
        return $this->hasOne(Domains::class, 'id', 'domain_id');
    }

    public function histories()
    {
        return $this->hasMany(HistoryDns::class, 'record_id', 'id');
    }
}
