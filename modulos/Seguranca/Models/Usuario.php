<?php

namespace Modulos\Seguranca\Models;

use App\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuario extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'seg_usuarios';

    /**
     * Table Primary key and autoincrement.
     *
     * @var string
     */
    protected $primaryKey = 'usr_pes_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['usr_usuario','usr_ativo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['usr_senha', 'remember_token'];

    public static $rules = [
        'usr_senha' => 'required|min:6',
        'usr_usuario' => 'required',
        'usr_ativo' => 'required'
    ];

    public function perfis()
    {
        return $this->belongsToMany('Modulos\Seguranca\Models\Perfil', 'seg_perfis_usuarios', 'pru_usr_id', 'pru_prf_id');
    }

    public function pessoa()
    {
        return $this->belongsTo('Modulos\Geral\Models\Pessoa', 'usr_pes_id', 'pes_id');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->usr_senha;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}