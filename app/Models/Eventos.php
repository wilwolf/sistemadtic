<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_titulo',
        'id_user',
        'modalidad',
        'cargah',
        'fechainicio',
        'fechafin',
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_titulo' => 'integer',
        'id_user' => 'integer',
        'fechainicio' => 'date',
        'fechafin' => 'date',
    ];


    public function users()
    {
        return $this->belongsToMany(\App\Models\Users::class);
    }

    public function idTitulo()
    {
        return $this->belongsTo(\App\Models\IdTitulo::class);
    }

    public function idUser()
    {
        return $this->belongsTo(\App\Models\IdUser::class);
    }
}
