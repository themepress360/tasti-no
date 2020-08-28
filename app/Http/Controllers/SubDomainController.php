<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Traits\ValidationTrait as ValidationTrait;
use App\Restaurants as Restaurants;
use App\User as User;
use Hash;
use App\Traits\TableTrait as TableTrait;

class SubDomainController extends CommonController
{
    use TableTrait;
	use ValidationTrait;
	function index()
	{
		echo $this->view('welcome',[]);
	}

    public function adminlogin(Request $request)
    {
        echo $this->view('subadmin',[]);
    }

    public function successlogin($subdomainID)
    {
        $is_subdomain_exists = Restaurants::where(['id' => (int) $subdomainID,"status" => '1' ,"deleted" => '0'])->first();
        $subdomain = (explode('.', $_SERVER['HTTP_HOST'])[0]);
        if(!empty($is_subdomain_exists) && $is_subdomain_exists['subdomain'] == $subdomain)
        {
            echo $this->view('successlogin',[]);
        }
        else
        {
            print_r("Invalid Subdomain Id");
            exit();
        }
    }

	/**
     * @desc Add Sub Domain and create primary user
     * @param  POST DATA (name,subdomain,email,password,confirmpassword)
     * @return Array()
     */

	public function subdomaincreate(Request $request)
	{
		$rules = [
            'name'      => 'required|min:3|max:30',
            'subdomain' => 'required|min:3|max:10',
            'email' => 'required|email',
            'password' => 'required|min:3|max:30',
            'confirmpassword' => 'required|min:3|max:30'
        ];

        $validator = Validator::make($request->all(),$rules);

        if (!$validator->fails()) {
        	$requestData = $request->all();
        	$custom_validate = $this->createsubdomain_validation($requestData);
        	if($custom_validate['status'])
        	{
                $user_table_name = strtolower($requestData['subdomain']).'_users';
                $user_table_fields = array(
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'email', 'type' => 'string','default' =>[]],
                    ['name' => 'password', 'type' => 'string','default' =>[] ],
                    ['name' => 'restaurant_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'is_superadmin', 'type' => 'integer', 'default' => []],
                    
                    ['name' => 'status', 'type' => 'enum','default' => ['1','0']],
                    ['name' => 'is_primary', 'type' => 'enum','default' => ['1','0']],
                    ['name' => 'deleted','type' => 'enum','default' => ['1','0']],
                    ['name' => 'email_verified_at','type' => 'timestamp','default' => []]
                );
                $this->createTable($user_table_name,$user_table_fields);

                $restaurant_table_name = strtolower($requestData['subdomain']).'_restaurants';
                $restaurant_table_fields = array(
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'subdomain', 'type' => 'string','default' =>[]],
                    ['name' => 'status', 'type' => 'enum','default' => ['1','0']],
                    ['name' => 'deleted','type' => 'enum','default' => ['1','0']]
                );
                $this->createTable($restaurant_table_name,$restaurant_table_fields);
                
        		$add_restaurant = Restaurants::add([
                    'name'      => $requestData['name'],
                    'subdomain' => strtolower($requestData['subdomain']),
                    'status'    => '1',
                    'deleted'   => '0',
                ]);
        		if($add_restaurant)
        		{
                    $data['restaurant_id'] = $add_restaurant['id'];
        			$add_user = User::create([
                        'name'      => $requestData['name'],
                        'email' => strtolower($requestData['email']),
                        'restaurant_id' => (int) $add_restaurant['id'],
                        'password' => \Hash::make($requestData['password']),
                        'is_primary' => '1',
                        'status'    => '1',
                        'deleted'   => '0',
                        'is_superadmin' => 0
                	]);
                	if($add_user)
                	{
                        $status = 201;
                        $response = array(
                            'status' => 'SUCCESS',
                            'data'   => ['restaurant_id' => $add_restaurant['id']],
                            'ref'    => 'restaurant_created',
                        );
                	}
                	else
                	{
                		$status = 500;
	                    $response = array(
	                        'status'  => 'FAILED',
	                        'message' => trans('messages.server_error'),
	                        'ref'     => 'server_error',
	                    );
                	}
        			
        		}
        		else
        		{
        			$status = 500;
                    $response = array(
                        'status'  => 'FAILED',
                        'message' => trans('messages.server_error'),
                        'ref'     => 'server_error',
                    );
        		} 
            }
            else
	        {
	            $status = 400;
	            $response = array(
	                'status'  => 'FAILED',
	                'message' => $custom_validate['message'],
	                'ref'     => $custom_validate['ref'],
	            );
	        }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }

        return $this->response($response,$status);  
	}


    public function ready($subdomainID)
    {
        $is_subdomain_exists = Restaurants::where(['id' => (int) $subdomainID,"status" => '1' ,"deleted" => '0'])->first();
        if($is_subdomain_exists)
        {
            $data['is_subdomain_exists'] = $is_subdomain_exists;
            echo $this->view('ready',$data);
        }
        else
        {
            print_r("Invalid Subdomain Id");
            exit();
        }
    }
    /**
     * @desc Add Sub Domain and create primary user
     * @param  POST DATA (subdomain,email,password)
     * @return Array()
     */
    public function subdomainuserlogin(Request $request)
    {
        $rules = [
            'subdomain' => 'required|min:3|max:10',
            'email' => 'required|email',
            'password' => 'required|min:3|max:30'
        ];

        $validator = Validator::make($request->all(),$rules);

        if (!$validator->fails()) {
            $requestData = $request->all();
            $custom_validate = $this->subdomainuserlogin_validation($requestData);
            if($custom_validate['status'])
            {
                $status = 201;
                $response = array(
                    'status' => 'SUCCESS',
                    'data'   => ['restaurant_id' => $custom_validate['restaurant']['id']],
                    'ref'    => 'login_success',
                );
            }
            else
            {
                $status = 400;
                $response = array(
                    'status'  => 'FAILED',
                    'message' => $custom_validate['message'],
                    'ref'     => $custom_validate['ref'],
                );
            }
        } else {
            $status = 400;
            $response = array(
                'status'  => 'FAILED',
                'message' => $validator->messages()->first(),
                'ref'     => 'missing_parameters',
            );
        }
        return $this->response($response,$status);
    }
}