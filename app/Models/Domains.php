<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    protected $fillable = ['api_id', 'domain', 'zone_id'];

    public function account()
    {
        return $this->hasOne(AccountApi::class,'id', 'api_id');
    }
    public function record()
    {
        return $this->hasMany(RecordList::class,'domain_id', 'id');
    }
}
