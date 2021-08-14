<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventosRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class EventosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EventosCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Eventos::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/eventos');
        CRUD::setEntityNameStrings('eventos', 'eventos');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {   
       // CRUD::column('id_titulo');
        $this->crud->addColumns([
            [
                'name'      =>'id_titulo',
                'label'     => 'Titulo',
                'type'      => 'select',
                'entity'    => 'titulos',
                'attribute' => 'nombre',
                'model'     => '\App\Models\Titulos',
                'wrapper'   => [
                                'href' => function ($crud, $column, $entry, $related_key){
                                    
                                    return backpack_url('titulos/'.$related_key.'/show');
                                }
                            ]

            ],
            [
                'name'  => 'modalidad',
                'label' => 'Modalidad'
            ],
            [
                'name'  => 'version',
                'label' => 'Version',
                'type'  => 'number'

            ],
            [
                'name'  => 'estado',
                'label' => 'Estado',
                'type'  => 'boolean',
                'options' => [0 => 'Creado', 1 => 'En Curso', 2 => 'Finalizado'],
                'wrapper' => [
                    'element' => 'span',
                    'class' => function ($crud, $column, $entry, $related_key) {
                        if ($column['text'] == 'Creado') {
                            return 'badge badge-warning';
                        }
                        if ($column['text'] == 'En Curso') {
                            return 'badge badge-success';
                        }
                        if ($column['text'] == 'Finalizado') {
                            return 'badge badge-alert';
                        }            
                        return 'badge badge-default';
                    },
                ],
            ],
            [
                'name'      =>'id_user',
                'label'     => 'Docente',
                'type'      => 'select',
                'entity'    => 'users',
                'attirbute' => 'name',
                'model'     => '\App\User',
                'wrapper'   => [
                                'href' => function ($crud, $column, $entry, $related_key){
                                    return backpack_url('user/'.$related_key.'/show');
                                }
                            ]

            ],
            [
                'name'  => 'fechainicio',
                'label' => 'Inicio',
                'type'  => 'date'

            ],
            [
                'name'  => 'fechafin',
                'label' => 'Conclusion',
                'type'  => 'date'

            ],
            


            


          ]
        );
        
      
        
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        $this->crud->addButtonFromModelFunction('line', 'id', 'botonLlenadoNotas', 'beginning');
        $this->crud->removeButton('show');
        $this->addCustomCrudFilters();
        $this->crud->enableExportButtons();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EventosRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
        $this->crud->addFields(static::getFieldsArrayForDatosTab());
        


        CRUD::field('id_titulo')
            ->type('select2')
            ->label('Titulo')
            ->entity('Titulo')
            ->attribute('nombre')
            ->model('\App\Models\Titulos');
        CRUD::field('id_user')
            ->type('select2')
            ->label('Docente')
            ->entity('users')
            ->attribute('name')
            ->model('\App\User');
        

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
     * Funciones agregadas
     */
    public static function getFieldsArrayForDatosTab(){
        return [
            [   
                'name'  => 'id_titulo',
                'label' => 'Titulo',
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'estado',
                'label' => 'Estado del Elevento',
                'type'  => 'radio',
                'options' => [
                        0 => 'Creado',
                        1 => 'En curso',
                        2 => 'Finalizado'
                ],
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'id_user',
                'label' => 'Docente',
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'cargah',
                'label' => 'Carga Horaria',
                'type'  => 'number',
                'tab'   =>'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'], 
            ],
            [
                'name'   => 'modalidad',
                'label'  => 'Modalidad',
                'type'   => 'select_from_array',
                'options'=> ['virtual'=> 'Virtual', 'precencial'=>'Precencial', 'semiprecencial'=> 'Semi Precencial'],
                'allows_null'   => true,
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],

            ],
            [   
                'name'  => 'version',
                'label' => 'Version',
                'type'  => 'number',
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'fechainicio',
                'label' => 'Fecha de Inicio',
                'type'  => 'date',
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'fechafin',
                'label' => 'Fecha de Conclusion',
                'type'  => 'date',
                'tab'   => 'Datos Evento',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            ///////////PARA EL SEGUNDO
            [
                'name'  => 'nombrex',
                'type'  => 'number',
                'label' => 'Posicion nombre X',
                'tab'   => 'Datos Certificado',
                'default' => 0,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'nombrey',
                'type'  => 'number',
                'label' => 'Posicion nombre Y',
                'default' => 0,
                'tab'   => 'Datos Certificado',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'qrx',
                'label' => 'Posicion del QR X',
                'type'  => 'number',
                'default' => 0,
                'tab'   => 'Datos Certificado',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'qry',
                'label' => 'Posicion del QR Y',
                'type'  => 'number',
                'tab'   => 'Datos Certificado',
                'default' => 0,
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'contenido',
                'type' => 'ckeditor',
                'label' => 'Texto del certificado',
                'default' => 'Titulo del certificado',
                'tab'   => 'Datos Certificado',
                'wrapperAttributes' => ['class' => 'form-group col-md-6'],
            ]

            
        ];
    }
    /**
     * FILTROS PERSONALIZADOS
     */
    protected function addCustomCrudFilters()
    {
        $this->crud->addFilter([ // select2 filter
            'name' => 'id_titulo',
            'type' => 'select2',
            'label'=> 'Titulo',
        ], function () {
            return DB::table('eventos')->select('eventos.id as id', 'titulos.nombre as name')
                        ->join('titulos','titulos.id', '=', 'eventos.id_titulo')
                        ->get()->keyBy('id')->pluck('name', 'id')->toArray();
            //return \Backpack\NewsCRUD\app\Models\Category::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'eventos.id', $value);
        });

        

        
    }

}
