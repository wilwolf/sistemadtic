<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\UrlGenerator;

class Inscripciones extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_evento',
        'id_estudiante',
        'id_fuente',
        'estado',
        'nota',
        'monto',
        'deposito',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_evento' => 'integer',
        'id_estudiante' => 'integer',
        'id_fuente' => 'integer',
    ];


    public function eventos()
    {
        return $this->belongsTo(\App\Models\Eventos::class, 'id_evento');
    }

    public function estudiantes()
    {
        return $this->belongsTo(\App\Models\Estudiantes::class,'id_estudiante');
    }

    public function fuentes()
    {
        return $this->belongsTo(\App\Models\Fuentes::class, 'id_fuente');
    }

    public function idEvento()
    {
        return $this->belongsTo(\App\Models\IdEvento::class);
    }

    public function idEstudiante()
    {
        return $this->belongsTo(\App\Models\IdEstudiante::class);
    }

    public function idFuente()
    {
        return $this->belongsTo(\App\Models\IdFuente::class);
    }
    ////////////////////////////////////////////////////////////////////
    /**FUNCIONES WQI */
    ////////////////////////////////////////////////////////////////////

    /**
     * Traer NOmbre y Apellido de un Usuario
     */
    public function getApellidosAndNombreAttribute(){
        $user = \App\Models\Estudiantes::select(DB::raw('CONCAT(estudiantes.apellidos, " ",estudiantes.nombre) as nombre '))
            ->where('id',$this->id_estudiante)
            ->get()->toArray();
        return $user[0]['nombre'];
    }
    
    /**
     * Trae Titulo de un Curso
     */
    public function getEventoTitulo(){
        $datos = \App\Models\Titulos::select( DB::raw('CONCAT(titulos.nombre, " | ",eventos.modalidad, "-",eventos.version) as nombreevento ') )
                                ->join('eventos', 'titulos.id', '=', 'eventos.id_titulo') 
                                -> whereIn('eventos.estado', ['0','1'])
                                -> where('eventos.id', $this->id_evento)
                                ->get()->toArray();
        return $datos[0]['nombreevento'];
    }

    /**
     * Genera Botton imprimir certificado
     */
    public function botonImprimirCertificado(){
        return '<a class="btn btn-warning btn-sm  " target="_blank" href="'.backpack_url('inscripciones/pdf').'/'.$this->id.'/'.$this->id_evento.'/'.$this->id_estudiante.'" data-toggle="tooltip" title="Para imprimir el Certificado."><i class="la la-file-pdf-o"></i> </a>';
    }




}
