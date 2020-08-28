<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


trait TableTrait
{
    /**
     * Create dynamic table along with dynamic fields
     *
     * @param       $table_name
     * @param array $fields
     *
     * @return \Illuminate\Http\JsonResponse
     */    
    public function createTable($table_name, $fields = [])
    {
         // check if table is not already exists
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        if($field['type'] == 'enum')
                        {
                            $table->{$field['type']}($field['name'],$field['default']);
                        }
                        else
                        {
                            $table->{$field['type']}($field['name']);
                        }
                    }
                }
                // if(strpos($table_name,'_users') > 0)
                // {
                //     $table->rememberToken();
                // }
                $table->timestamps();
            });
            return true;
            //return response()->json(['message' => 'Given table has been successfully created!'], 200);
        }
        return false;
        //return response()->json(['message' => 'Given table is already existis.'], 400);
    }

    public function operate()
    {
        // set dynamic table name according to your requirements

        $table_name = 'demo';

        // set your dynamic fields (you can fetch this data from database this is just an example)
        $fields = [
            ['name' => 'field_1', 'type' => 'string'],
            ['name' => 'field_2', 'type' => 'text'],
            ['name' => 'field_3', 'type' => 'integer'],
            ['name' => 'field_4', 'type' => 'longText']
        ];

        return $this->createTable($table_name, $fields);
    }

     /**
     * To delete the tabel from the database 
     * 
     * @param $table_name
     *
     * @return bool
     */    
    public function removeTable($table_name)
    {
        Schema::dropIfExists($table_name); 
        
        return true;
    }
}