<?php

namespace App\Models\Security;

use Illuminate\Auth\Authenticatable;
use App\Models\BaseModel;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuario extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

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
    protected $primaryKey = 'usr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['usr_nome', 'usr_email', 'usr_telefone', 'usr_usuario', 'usr_senha', 'usr_ativo'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['usr_senha', 'remember_token'];

    public function perfil()
    {
        return $this->belongsToMany('App\Models\Security\Perfil', 'seg_perfis_usuarios', 'pru_usr_id', 'pru_prf_id');
    }

    public static $rules = [
        'usr_nome' => 'required|min:3',
        'usr_email' => 'required|email',
        'usr_usuario' => 'required|min:3',
        'usr_ativo' => 'required'
    ];

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->usr_email;
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