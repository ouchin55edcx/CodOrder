<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'email',
        'password',
        'agree_to_terms',
        'verification_token',
        'trial_ends_at',
        'email_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Check if the user is on a trial period.
     *
     * @return bool
     */
    public function isOnTrial()
    {
        return Carbon::parse($this->created_at)->addDays(3)->isFuture();
    }
}