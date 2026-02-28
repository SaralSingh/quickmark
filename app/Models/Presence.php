<?php

namespace App\Models;

use App\Models\Person;
use App\Models\PramanSession;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'praman_session_id',
        'person_id',
        'person_name',
        'is_present',
    ];
        protected $casts = [
        'is_present' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(PramanSession::class, 'praman_session_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
