<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

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
                  'first_name',
                  'last_name',
                  'contact'
              ];



    /**
     * Get user by name and last name
     *
     * @param string $first_name
     * @param string $last_name
     * @return void
     */
    public static function getByName($first_name, $last_name){
        return Client::where( "first_name", $first_name )
                    ->where( "last_name", $last_name )
                    ->first();
    }
    /**
     * Find user by first and last name or create otherwise
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $contact
     * @return void
     */
    public static function findOrCreate($first_name, $last_name, $contact)
    {
        $client =self::getByName(trim($first_name), trim($last_name));
        if(!isset($client)){
            $client = Client::create([
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'contact'    => $contact
            ]);
        }
        return $client->id;
    }




}
