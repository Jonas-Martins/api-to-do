<?php

namespace App\Models;

use APP\Models\User;
use APP\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'color', 'user_id'
    ];

    // protected $hidden = [
    //     'user_id',
    // ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tasks(){
        return $this->hasMany(\App\Models\Task::class);
    }
}
