<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;

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
        CRUD::addColumn([
            'name'  => 'id_estudiante',
            'label' => 'Estudiante',
            'type'  => 'model_function',
            'function_name' => 'getApellidosAndNombreAttribute',
           /*'searchLogic'   => function ($query, $column, $searchTerm) {
                $query->orWhere('nombre','like','%'.$searchTerm.'%');
                $query->orWhere('apellidos', 'like', '%'.$searchTerm.'%');
            }*/
        ]);
        CRUD::addColumn([
                'name'  => 'estado',
                'label' => 'Estado',
                'type'  => 'boolean',
                'options' => [0=>'OK', 1=>'Pago/Deposito', 2=>'Otra razon'],  
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        if ($column['text'] == 'OK') {
                            return 'badge badge-success';
                        }
                        if ($column['text'] == 'Pago/Deposito') {
                            return 'badge badge-error';
                        }
                        if ($column['text'] == 'Otra razon') {
                            return 'badge badge-error';
                        }                        
                    },
                ],
        ]);
        CRUD::addColumn([
            'name' => 'nota',
            'label' => 'Nota',
            'type' => 'number',
            'wrapper' => [
                'element' => 'span',
                'class' => function($crud, $column, $entry, $related_key){
                    if($column['text']==0){
                        return 'badge badge-error';
                    }
                    if($column['text'] > 0 and $column['text'] < 71){
                        return 'badge badge-warning';
                    }
                    if($column['text']>=71){
                        return 'badge badge-success';
                    }
                }
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
    

}
