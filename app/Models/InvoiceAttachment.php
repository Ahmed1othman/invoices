<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InvoiceAttachment extends Model
{
    use HasFactory;
    protected $fillable=[
        'admin_id','invoice_id','file_name'
    ];

    protected $hidden = [];
    public $timestamps = true;

    //relations
    public function invoice(){
        return $this->belongsTo('App\Models\Invoice','invoice_id','id');
    }

    public function createdBy(){
        return $this->belongsTo('App\Models\Admin','admin_id','id');
    }

}
