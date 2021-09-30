<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use MenuMapping;
use Role;
use App\Models\Model\model_has_permissions as UserPermissions;

class User extends Authenticatable
{
    use HasRoles,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'    => 'datetime:d-m-Y',
    ];

    public function assigned_menus() {

        return $this->hasMany(MenuMapping::class,'user_id');
    }

    public function alloted_permissions() {
        return $this->hasMany(UserPermissions::class, 'model_id', 'id');
    }
}
