<?php

namespace App\Models\Mobile;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    
    protected $guard = 'mobile';
    protected $table = 'mobile_users';
    protected $guarded = [];

}
