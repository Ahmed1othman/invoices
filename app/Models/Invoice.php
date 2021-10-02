<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\invoiceObserver;
class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "invoices";


    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'product_id',
        'section_id',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'status',
        'value_status',
        'note',
        'amount_commission',
        'amount_collection',
        'payment_date',
        'admin_id',
    ];

    protected static function boot()
    {
        parent::boot();
        Invoice::observe(invoiceObserver::class);
    }

    protected $hidden = [];

    //relations
    public function product(){
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

    public function section(){
        return $this->belongsTo('App\Models\Section','section_id','id');
    }

    public function createdBy(){
        return $this->belongsTo('App\Models\Admin','admin_id','id');
    }

    public function attachments(){
        return $this->hasMany('App\Models\InvoiceAttachment','invoice_id','id');
    }

    public function logs(){
        return $this->hasMany('App\Models\InvoiceLog','invoice_id','id');
    }
}
