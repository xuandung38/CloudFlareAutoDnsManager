<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountApi extends Model
{
    protected $fillable = ['email', 'api_key'];

    public function domain()
    {
        return $this->hasMany(Domains::class, 'api_id', 'id');
    }
}
