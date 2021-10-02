<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id','section_id','product_id','admin_id','status','value_status','note'
    ];
    protected $dates = ['deleted_at'];

    protected $hidden = [];



    // Relations //
    // return invoice have the log
    public function invoice(){
        return $this->belongsTo('App/Models/Invoice','invoice_id','id');
    }
    // return the section of invoice
    public function section(){
        return $this->belongsTo('App/Models/Section','section_id','id');
    }
    // return the product of invoice
    public function product(){
        return $this->belongsTo('App/Models/Product','product_id','id');
    }
    // return the admin who log invoice
    public function byAdmin(){
        return $this->belongsTo('App/Models/Admin','admin_id','id');
    }
}
