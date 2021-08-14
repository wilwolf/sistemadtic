<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionesRequest;
use App\Models\Inscripciones;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use PhpParser\Node\Expr\Exit_;

/**
 * Class InscripcionesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InscripcionesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
  
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Inscripciones::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inscripciones');
        CRUD::setEntityNameStrings('inscripciones', 'inscripciones');
        
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->removeButton('show');
        CRUD::addColumn([
            'name' => 'id_evento',
            'label'=> 'Evento',
            'type' => 'model_function',
            'function_name' => 'getEventoTitulo',
            /*'searchLogic'   => function($query, $column, $searchTerm){
                $query->orWhere('nombre','like','%'.$searchTerm.'%');
            },*/
            'searchLogic' => 'text',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('eventos/'.$entry->id_evento.'/show');
                },
            ],

        ]);
        

        $this->crud->addColumn([
            'label' => 'Apellidos',
            'type'  => 'select',
            'name'  => 'id_estudiante',
            'entity' => 'estudiantes',
            'attribute' => 'apellidos',
            'model' => '\App\Models\Inscripciones',
            'orderable' => true,
            'orderLogic' =>  function ($query, $column, $columnDirection) {
                return $query->leftJoin('estudiantes', 'estudiantes.id', '=', 'inscripciones.id_estudiante')
                    ->orderBy('estudiantes.apellidos', $columnDirection)->select('inscripciones.*');
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Nombre(es)',
            'type'  => 'select',
            'name'  => 'id_estudiante',
            'entity' => 'estudiantes',
            'key'   => 'estudiantes_nombre',
            'attribute' => 'nombre',
            'model' => '\App\Models\Inscripciones',
            'orderable' => true,
            'orderLogic' =>  function ($query, $column, $columnDirection) {
                return $query->leftJoin('estudiantes', 'estudiantes.id', '=', 'inscripciones.id_estudiante')
                    ->orderBy('estudiantes.nombre', $columnDirection)->select('inscripciones.*');
            }
        ]);
        CRUD::addColumn([
                'name'  => 'estado',
                'label' => 'Estado',
                'type'  => 'select_from_array',
                'options' => [0=>'OK', 1=>'Pago/Deposito', 2=>'Otra razon '],  
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        if ($entry->estado == 0) {
                            return 'badge badge-success';
                        }
                        else if ($entry->estado == 1) {
                            return 'badge badge-error';
                        }
                        else if ($entry->estado == 2) {
                            return 'badge badge-warning';
                        }                        
                    },
                ],
        ]);
        CRUD::addColumn([
            'name' => 'nota',
            'label' => 'Nota',
            'type' => 'number',
            'wrapper' => [
                'element' => 'a',
                'href' => '#',
                'class' => function($crud, $column, $entry, $related_key){
                    if($column['text']==0){
                        return 'badge badge-error xedit';
                    }
                    if($column['text'] > 0 and $column['text'] < 71){
                        return 'badge badge-warning xedit';
                    }
                    if($column['text']>=71){
                        return 'badge badge-success xedit';
                    }
                },
                'data-pk' => function($crud, $column, $entry,$related_key){
                    return $entry->id; 
                },
                'data-name' => 'nota',



            ]

        ]);
        CRUD::addColumn([
            'name'  => 'id_fuente',
            'label' => 'Fuente',
            'type'  => 'select',
            'entity' => 'fuentes',
            'attribute' =>  'nombre',
            'model' => '\App\Models\Fuentes',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('fuentes/'.$related_key.'/show');
                },
            ],
        ]);
        CRUD::column('id_fuente');
        CRUD::column('monto')->type('number');
        CRUD::column('deposito');

        $this->crud->addButtonFromModelFunction('line', 'id_fuente', 'botonImprimirCertificado', 'beginning');
        $this->crud->enableDetailsRow();
        $this->crud->setDetailsRowView('vendor.backpack.crud.details_row.inscripcion');
        $this->crud->enableExportButtons();
        $this->addCustomCrudFilters();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->set('show.contentClass', 'col-md-12');
        $this->eventoTitulo();
        CRUD::setValidation(InscripcionesRequest::class);
        
        CRUD::addField(
            [   'name'        => 'id_evento',
                'label'       => "Evento",
                'type'        => 'select2_from_array',
                'options'     => $this->eventoTitulo(),
                'allows_null' => false,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
        );

        CRUD::addField(
            [
                'name'  => 'id_estudiante',
                'label' => 'Carnet Estudiante',
                'type'  => 'select2_from_array',
                'options' => $this->listaEstudiantes(),
                'allows_null' => false,
                'default'   => 0,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ]
        );

        
        CRUD::addfield(
            [
                'name'   => 'id_fuente',
                'Label'  => 'Forma de inscripción', 
                'type'   => 'select2',
                'entity' => 'fuentes',
                'model'  => '\App\Models\Fuentes',
                'attribute' => 'nombre',
                'default' => 1,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ]
        );
        CRUD::addField(
            [
                'name'  => 'estado',
                'label' => 'Estado de Inscripción',
                'type'  => 'select_from_array',
                'options' => [0=>'Confirmado', 1=>'Pendiente de Pago/Deposito', 2=>'pendiente por otra razon'],
                'default' => 0,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ]
        );
        
        CRUD::field('monto')->size(4)->default(50);
        CRUD::field('nota')->size(4)->default('0');
        
        CRUD::field('deposito')->size(4)->default('0');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * FUNCIONES EXTRAS
     */
    
    /**
     *  LISTA DE TODOS LOS EVENTOS ACTIVOS
     */
     public function eventoTitulo(){
        $nuevoArray=array();
        $datos = \App\Models\Titulos::select( 'eventos.id as id_evento', DB::raw('CONCAT(titulos.nombre, " | ",eventos.modalidad, "-",eventos.version) as nombre ') )
                                ->join('eventos', 'titulos.id', '=', 'eventos.id_titulo') 
                                -> whereIn('eventos.estado', ['0','1'])
                                ->get()->toArray();
        foreach($datos as $dato){
            $nuevoArray[$dato['id_evento']] =  $dato['nombre'];
        }
                                return $nuevoArray;
    }

    /**
     * LISTA DE ESTUDIANTES
     */
    public function listaEstudiantes(){
        $nuevoArray = array();
        $datos = \App\Models\Estudiantes::select('estudiantes.id', DB::raw('CONCAT(estudiantes.carnet,estudiantes.complemento, "|",estudiantes.nombre," ",estudiantes.apellidos) as nombre')) 
                                      ->get()->toArray();
        foreach($datos as $dato){
            $nuevoArray[$dato['id']] = $dato['nombre'];
        }    
        return $nuevoArray;
    }

    /**
     * PARA GENERAR EL PDF DEL CERTIFICADO
     */
    //public function createPdfCertificado($id,$estudiante, $evento){
    public function createPdfCertificado($id,$evento,$estudiante){
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
                      ->where('inscripciones.id',$id)
                      ->where('inscripciones.id_evento', $evento)
                      ->where('inscripciones.id_estudiante', $estudiante)
                      ->get()->toArray();
        $pdf = PDF::loadView('vendor.backpack.crud.certificado', ['certificado'=>$certificado]);
        $pdf->setPaper('letter','landscape');
        return $pdf->download($certificado[0]->carnet.'-'.$certificado[0]->evento.'-'.$certificado[0]->id.'-Certificado.pdf');
    }
    /**
     * FILTROS PERSONALIZADOS
     */
    protected function addCustomCrudFilters()
    {
        $this->crud->addFilter([ // select2 filter
            'name' => 'id_evento',
            'type' => 'select2',
            'label'=> 'Eventos',
        ], function () {
            return DB::table('inscripciones')->select('inscripciones.id_evento as id', 'titulos.nombre as name')
                        ->join('eventos', 'eventos.id', '=', 'inscripciones.id_evento')
                        ->join('titulos','titulos.id', '=', 'eventos.id_titulo')
                        ->get()->keyBy('id')->pluck('name', 'id')->toArray();
            //return \Backpack\NewsCRUD\app\Models\Category::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'id_evento', $value);
        });

        $this->crud->addFilter([ // select2 filter
            'name' => 'id_estudiante',
            'type' => 'select2',
            'label'=> 'Apellidos',
        ], function () {
            return DB::table('inscripciones')->select('inscripciones.id_estudiante as id', 'estudiantes.apellidos  as name')
                        ->join('estudiantes', 'estudiantes.id', '=', 'inscripciones.id_estudiante')
                        ->distinct()->orderBy('estudiantes.apellidos', 'ASC')
                        ->get()->keyBy('id')->pluck('name', 'id')->toArray();            
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'id_estudiante', $value);
        });

        
    }
    /**
     * ACTUALIZACION DE NOTAS EN VIVO
     */
    public function updateNotas(Request $request){
        if ($request->ajax()) {
           //print_r($request->pk);
            //print_r(Inscripciones::find($request->pk)->toArray());
            $inscripcion = Inscripciones::find($request->pk);
            $inscripcion->nota = $request->value;
            if($inscripcion->save()){
                return response()->json(['success' => true]);
            }
            else{
                return response()->json(['error' => true]);
            }
  

        }
    }

    /***
     * PARA EL REGISTRO DE NOTAS
    */
    public function notas($evento){
       $detalles = DB::table('eventos')
                ->select('titulos.nombre as tituloe','users.name as docente', 'eventos.version as version', 'eventos.modalidad as modalidad')
                ->join('titulos', 'titulos.id', '=', 'eventos.id_titulo')
                ->join('users', 'users.id', '=', 'eventos.id_user')
                ->where('eventos.id', '=', $evento) 
                ->get()->toArray();
        $notas = DB::table('inscripciones')
        ->select('estudiantes.carnet as carnet', 'estudiantes.apellidos as apellidos', 'estudiantes.nombre as nombre', 'inscripciones.nota as nota', 'inscripciones.id as id' ) 
        ->join('estudiantes', 'estudiantes.id', '=', 'inscripciones.id_estudiante')
        ->join('eventos', 'eventos.id', '=', 'inscripciones.id_evento')
        ->orderBy('estudiantes.apellidos', 'ASC')
        ->orderBy('estudiantes.nombre', 'ASC')

        ->where('inscripciones.id_evento', '=', $evento)->get();
        
        //print_r(count($notas));
        return View::make('notas', ['notas'=> $notas, 'detalles'=>$detalles]);
    } 

}
