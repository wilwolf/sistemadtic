<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TitulosRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TitulosCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TitulosCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Titulos::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/titulos');
        CRUD::setEntityNameStrings('titulos', 'titulos');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        $this->crud->addColumns([
            [
                'name'      =>'id_tipo',
                'label'     => 'Tipo',
                'type'      => 'select',
                'entity'    => 'tipo',
                'attirbute' => 'nombre',
                'model'     => '\App\Models\Tipo',
                'wrapper'   => [
                                'href' => function ($crud, $column, $entry, $related_key){
                                    return backpack_url('tipo/'.$related_key.'/show');
                                }
                            ]

            ],
            'nombre',
            [
              'name'    => 'descripcion',
              'label'   => 'Descripcion',
              'type'    => 'text'  
            ],
            [
                'name'  => 'created_at',
                'label' => 'Fecha de Creacion',
                'type'  => 'date'
            ]   
            
        ]);
        
        CRUD::enableExportButtons();
        $this->addCustomCrudFilters();
    }   

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TitulosRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
        CRUD::field('descripcion')->type('ckeditor');
        CRUD::field('tipo_id')
            ->type('select2')
            ->label('Tipo')
            ->entity('tipo')
            ->attribute('nombre')
            ->model('app\Models\Tipo');

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
     * Agregados 
     */
    protected function addCustomCrudFilters(){
        CRUD::filter('tipo_id')
                ->type('select2')
                ->label('Tipo')
                ->values(function () {
                    return \App\Models\Tipo::all()->keyBy('id')->pluck('nombre', 'id')->toArray();
                })->whenActive(function ($value) {
                    //$value;exit();
                    CRUD::addClause('where', 'tipo_id', $value);
                });
    }
}

