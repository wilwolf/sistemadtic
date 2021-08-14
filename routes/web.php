<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('qr-code-g', function () {
    \QrCode::size(500)
        ->format('png')
        ->generate('ItSolutionStuff.com', public_path('images/qrcode.png'));
return view('qrCode');
});
/**INSCRIPCIONES */
Route::get('/admin/inscripciones/pdf/{id}/{evento}/{estudiante}', 'Admin\InscripcionesCrudController@createPdfCertificado');
Route::post('/admin/inscripciones/update','Admin\InscripcionesCrudController@update');


/**Verificar Certificados */
Route::get('/verificar/{codigo}','VerificarController@verificarCertificado');

/** Para registro de notas */
Route::get('/admin/inscripciones/notas/{id}', 'Admin\InscripcionesCrudController@notas');

/** Para actualizar las notas de los estudiantes */
Route::post('/admin/inscripciones/updatenotas', 'Admin\InscripcionesCrudController@updateNotas')->name('updatenotas');