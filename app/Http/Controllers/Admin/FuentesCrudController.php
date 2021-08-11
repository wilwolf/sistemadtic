<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FuentesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FuentesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FuentesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Fuentes::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/fuentes');
        CRUD::setEntityNameStrings('fuentes', 'fuentes');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('nombre');
        CRUD::column('cuenta');
        $this->crud->addColumns([
            [
                'name'  => 'estado',
                'label' => 'Estado',
                'type'  => 'boolean',
                
                'options' => [0 => 'Deshabilitado', 1 => 'Habilitado'],
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        if ($column['text'] == 'Habilitado') {
                            return 'badge badge-success';
                        }
                        if ($column['text'] == 'Deshabilitado') {
                            return 'badge badge-error';
                        }
                        return 'badge badge-default';
                    },
                ],
            ],
        ]);
        CRUD::column('descripcion');
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
        CRUD::setValidation(FuentesRequest::class);

        CRUD::field('nombre')->type('text')->size(6);
        CRUD::field('cuenta')->type('text')->size(6);
        CRUD::field('descripcion')->type('ckeditor');
        CRUD::field('estado');

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
}
