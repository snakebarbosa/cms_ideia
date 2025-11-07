<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\HasRolesAndPermissions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable {
	use HasRolesAndPermissions;
	use Notifiable;
	use LogsActivity;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'estado', 'updated_at',
	];

	protected static $logAttributes = ['*'];

	protected static $logName = 'Utilizador';

	protected static $logOnlyDirty = true;
	
	 public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
	

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function roles() {
		return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
	}

	// public function historicos() {
	// 	return $this->hasMany('App\Historico', 'id_user');
	// }
}
