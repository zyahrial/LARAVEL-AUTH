<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // use HasFactory;
    protected $table = 'members';
    protected $fillable = ['user_id', 'status', 'position'];
    protected $primaryKey = 'id_member';

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}