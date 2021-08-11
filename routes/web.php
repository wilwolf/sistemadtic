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

Route::get('/admin/inscripciones/pdf/{id}/{evento}/{estudiante}', 'Admin\InscripcionesCrudController@createPdfCertificado');

/**Verificar Certificados */
Route::get('/verificar/{codigo}','VerificarController@verificarCertificado');