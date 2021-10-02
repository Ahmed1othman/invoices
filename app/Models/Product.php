<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'product_name',
        'description',
        'section_id',
    ];

    protected $hidden = [];


    // relations
    public function section(){
        return $this->belongsTo('App\Models\Section','section_id','id');
    }

    public function invoices(){
        return $this->hasMany('App\Models\Invoice','product_id','id');
    }
}
