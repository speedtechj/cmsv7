<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Branch;
use App\Models\Sender;
use App\Models\Citycan;
use App\Models\Provincecan;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasPanelShield;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    
   
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'file_doc' => 'array',
    ];
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function canAccessPanel(Panel $panel): bool
    {
        $user_role = auth()->user()->getRoleNames();
        if ($panel->getId() === 'monitoring' && $user_role->contains('super_admin')) {

            return str_ends_with($this->is_active, 1);
        } else
        if ($panel->getId() === 'admin' && $user_role->contains('super_admin')) {

            return str_ends_with($this->is_active, 1);
        } else if ($panel->getId() === 'appuser') {

            if ($user_role->contains('super_admin') || $user_role->contains('Encoder')) {
                return str_ends_with($this->is_active, 1);
            } else {
                return false;
            }
        } else if ($panel->getId() === 'twelve24') {
            if ($user_role->contains('super_admin') || $user_role->contains('1224')) {
                return str_ends_with($this->is_active, 1);
            } else {
                return false;
            }
        
        } 
        else if ($panel->getId() === 'allport') {
            if ($user_role->contains('super_admin') || $user_role->contains('allport')) {
                return str_ends_with($this->is_active, 1);
            } else {
                return false;
            }
        }
        else if ($panel->getId() === 'willxpress') {
            
            if ($user_role->contains('super_admin') || $user_role->contains('willxpress')) {
                return str_ends_with($this->is_active, 1);
            } else {
                return false;
            }
           
        }
        else {

            return false;
        }


    }
    public function isAdmin(): bool
    {
        return auth()->user()->getRoleNames()->contains('super_admin');

    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function citycan()
    {
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan()
    {
        return $this->belongsTo(Provincecan::class);
    }

    
    // public function sender(){
    //     return $this->hasMany(Sender::class);
    // }
    // public function batch(){
    //     return $this->hasMany(Batch::class);
    // }
    // public function remarkstatus(){
    //     return $this->hasMany(Remarkstatus::class);
    // }
}
