<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ['requester','suggestion','stage','procedure_id','read'];
    protected $dates = ['created_at'];
    public function user(){
        return $this->belongsTo(User::class,'requester');
    }
    public function procedure(){
        return $this->belongsTo(Procedure::class);
    }
}
