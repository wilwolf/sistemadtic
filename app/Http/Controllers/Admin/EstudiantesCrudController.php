<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EstudiantesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class EstudiantesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EstudiantesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Estudiantes::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estudiantes');
        CRUD::setEntityNameStrings('estudiantes', 'estudiantes');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name'  => 'imagen', // The db column name
                'label' => 'Fot', // Table column heading
                'type'  => 'image',
            ],
        ]);
        CRUD::column('carnet');
        //CRUD::column('complemento');
        $this->crud->addColumns([
            [
                'name'      =>'extension',
                'label'     => 'Ext',
                'type'      => 'select',
                'entity'    => 'departamentos',
                'attribute' => 'acro',
                'model'     => '\App\Models\Departamentos',
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key){
                        
                        return backpack_url('departamentos/'.$related_key.'/show');
                    }
                ]
                

            ],
        ]);

        CRUD::column('nombre');
        CRUD::column('apellidos');
        CRUD::column('telefono');
        CRUD::column('email');
        
        

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
        CRUD::setValidation(EstudiantesRequest::class);
       // $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
       
        CRUD::field('carnet')->type('number')->size(6);
        CRUD::field('complemento')->type('text')->size(3);
        CRUD::field('extension')
            ->type('select2')
            ->size(3)
            ->label('Extension')
            ->entity('departamentos')
            ->attribute('acro')
            ->model('\App\Models\Departamentos');
        CRUD::field('nombre')->type('text')->size(6);
        CRUD::field('apellidos')->type('text')->size(6);
        $this->crud->addFields([ 
            
            [
            'name'              => 'email',
            'label'             => 'Correo Electronico',
            'type'              => 'text',
            'prefix'            => '@',
            'wrapperAttributes' => ['class' => 'form-group col-md-6']
            ],
            [
                'name' => 'telefono',
                'label'=>'Celular con Whatsapp',
                'type' => 'number',
                'prefix'=>'#',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],  
            ],
            [
                'name' => 'imagen',
                'label' => 'Fotografia',
                'filename'=>null,
                'type'         => 'image',
                'upload'       => true,
                'crop'         => true,
                'aspect_ratio' => 1,
                'prefix' => 'uploads/images/profile_pictures'    
            ]

        ]);
        
        //CRUD::field('email')->type('text')->size(6);
        

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
