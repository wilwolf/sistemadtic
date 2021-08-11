<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Estudiantes extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'carnet',
        'complemento',
        'extension',
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'imagen',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carnet' => 'integer',
        'extension' => 'integer',
        'telefono' => 'integer',
    ];


    public function departamentos()
    {
        return $this->belongsTo(\App\Models\Departamentos::class, 'extension');
    }

    public function extension(){
        return $this->belongsTo(\App\Models\Departamentos::class, 'extension');
    }

     /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getApellidosAndNombreAttribute(){
        return $this->apellidos.' '.$this->nombre;
    }  


    /**mutators wqi */
    public function setImagenAttribute($value)
    {
        $attribute_name = 'imagen';
        $disk = 'public'; // use Backpack's root disk; or your own
        $destination_path = 'uploads/images/profile_pictures';

        // if the image was erased
        if ($value == null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (Str::startsWith($value, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it from the root folder
            // that way, what gets saved in the database is the user-accesible URL
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        }
    }
}
