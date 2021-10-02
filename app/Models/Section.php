<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = "sections";

    protected $fillable = [
        'section_name',
        'description',
    ];

    protected $hidden = [];

    // relations
    public function products(){
        return $this->hasMany('App\Models\Product','section_id','id');
    }

    public function invoices(){
        return $this->hasMany('App\Models\Invoice','section_id','id');
    }

}
