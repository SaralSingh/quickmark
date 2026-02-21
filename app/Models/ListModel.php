<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListModel extends Model
{
    use SoftDeletes;

    protected $table = 'lists';

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function sessions()
    {
        return $this->hasMany(PramanSession::class, 'list_id');
    }

    public function people()
    {
        return $this->hasMany(Person::class, 'list_id');
    }
}
