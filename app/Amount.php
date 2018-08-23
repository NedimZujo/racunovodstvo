<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Amount extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'date', 'description', 'transfer'
    ];
}
