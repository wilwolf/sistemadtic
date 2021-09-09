<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Torann\Hashids\Facade\Hashids;

class VerificarController extends Controller
{
    //
    public function verificarCertificado($codigo){
        $arrayCodigo = Hashids::decode($codigo);
            //print_r(empty($arrayCodigo));exit();
                if(!empty($arrayCodigo)){
                    
                    $certificado = DB::table('inscripciones')
                                ->select('inscripciones.id as id', 'inscripciones.id_evento as evento', 'inscripciones.id_estudiante as estudiante', 'inscripciones.nota as nota',
                                'eventos.modalidad as modalidad', 'eventos.version as version','eventos.cargah as cargah',
                                'titulos.nombre as titulo', 'tipos.nombre as tipoe',
                                    'estudiantes.nombre as nombre','estudiantes.apellidos as apellidos','estudiantes.carnet as carnet',
                                    'eventos.fechainicio as inicio', 'eventos.fechafin as fin',
                                    'users.name as docente'
                                )
                                ->join('eventos','eventos.id', '=', 'inscripciones.id_evento')
                                ->join('titulos', 'titulos.id','=', 'eventos.id_titulo')
                                ->join('tipos','tipos.id', '=', 'titulos.tipo_id')
                                ->join('estudiantes', 'estudiantes.id', '=','inscripciones.id_estudiante')
                                ->join('users','users.id','=','eventos.id_user')
                                ->where('inscripciones.id',$arrayCodigo[0])
                                ->where('inscripciones.id_evento', $arrayCodigo[1])
                                ->where('inscripciones.id_estudiante', $arrayCodigo[2])
                                ->get()->toArray();
                        return view('verificarCertificado',['certificado'=> $certificado]);
                }else{
                    echo 'Codigo invalido ...';
                }
    }
}
