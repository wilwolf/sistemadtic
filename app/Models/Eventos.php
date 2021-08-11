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
        'version',
        'cargah',
        'fechainicio',
        'fechafin',
        'nombrex',
        'nombrey',
        'qrx',
        'qry',
        'contenido',
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


    public function titulos()
    {
        return $this->belongsTo(\App\Models\Titulos::class, 'id_titulo');
    }

    public function users()
    {
        return $this->belongsTo(\App\User::class, 'id_user');
    }


    /** Consultas personalizadas wqi */
    public function eventotitulo(){
        $data = \App\Models\Titulos::select('eventos.id as id', 'CONCAT(titulos.nombre, " ",eventos.version, "-",eventos.modalidad) as nombre')
                                    ->join('eventos', 'titulos.id', '=', 'eventos.id_titulo') 
                                    ->get();
        var_dump($data);
        return $data;
    }




}
