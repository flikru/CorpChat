<?php

namespace App\Models;
use App\Models\User;
use App\Models\Message;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{

    use HasFactory;
    protected $guarded=[];
    protected $fillable = [];

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function messages(){
        return $this->hasMany(Message::class)->orderBy('id','desc');
    }
}
