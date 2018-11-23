<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Log extends Model
{

     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'logs';


    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

       /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event',
        'object_type',
        'object_id',
        'user_id',
        'data'
    ];

    /**
     * Record interactions btw users and database objects
     * @param  string $event       	 Any of the next options CREATE, UPDATE, DELETE
     * @param  string $object_type 	 Name of the object being modified
     * @param  integer $object_id  	 Primary key used to identify the object
     * @param  object $object      	 The object to be saved as json in the DB
     * @return boolean               Result of trying to save the object in the database
     */
    public static function record( $event, $object_type, $object_id, $object)
    {
    	$user = Auth::user();
        $log = Log::create([
            'event' => $event,
            'object_type'  => $object_type,
            'object_id'    => $object_id,
            'user_id'    => $user->id,
            'data'    => json_encode($object)
        ]);
        return $log;
    }
}
