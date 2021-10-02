<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\This;
use Spatie\Permission\Traits\HasRoles;


class Admin extends Authenticatable
{

    use HasFactory, Notifiable;
    use HasRoles;
    protected $guard_name = 'admin';


    protected $table = "admins";

    protected $fillable = ['name','username', 'email', 'password','roles_name','Status'];

    protected $hidden = ['password', 'remember_token',];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles_name' => 'array',
    ];

    //relations
    public function invoices(){
        return $this->hasMany('App/Models/Invoice','admin_id','id');
    }


}
