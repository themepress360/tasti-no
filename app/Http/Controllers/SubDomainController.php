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
		echo $this->view('subdomain-registration.index',[]);
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
        
        $prefix = $request->subdomain;
       
           config(['database.connections.mysql.prefix' => "{$prefix}_"]);
          \DB::reconnect();
          \Artisan::call('migrate',
             array(
               '--path' => 'database/migrations',
               '--force' => true));
        
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

                /* Tastyignitor Table Structure */

                $activities_table_name = strtolower($requestData['subdomain']).'_activities';
                $activities_table_fields = array(
                    ['name' => 'activity_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'user_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'date_added', 'type' => 'datetime','default' =>[]],
                    ['name' => 'log_name', 'type' => 'string','default' =>[]],
                    ['name' => 'properties', 'type' => 'string','default' =>[] ],
                    ['name' => 'subject_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'causer_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'type', 'type' => 'string', 'default' => []],
                    ['name' => 'read_at', 'type' => 'datetime','default' => ['1','0']],
                    ['name' => 'deleted_at', 'type' => 'timestamp','default' => ['1','0']],
                    ['name' => 'user_type','type' => 'string','default' => ['1','0']]
                   
                );
                $this->createTable($activities_table_name,$activities_table_fields);

                $addresses_table_name = strtolower($requestData['subdomain']).'_addresses';
                $addresses_table_fields = array(
                    ['name' => 'address_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'customer_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'address_1', 'type' => 'string','default' =>[]],
                    ['name' => 'address_2', 'type' => 'string','default' =>[]],
                    ['name' => 'city', 'type' => 'string','default' =>[] ],
                    ['name' => 'state', 'type' => 'string', 'default' => []],
                    ['name' => 'postcode', 'type' => 'string', 'default' => []],
                    ['name' => 'country_id', 'type' => 'integer', 'default' => []],
                   
                );
                $this->createTable($addresses_table_name,$addresses_table_fields);

                $assignable_logs_table_name = strtolower($requestData['subdomain']).'_addresses';
                $assignable_logs_table_fields = array(
                    ['name' => 'id', 'type' => 'integer', 'default' => []],
                    ['name' => 'assignable_type', 'type' => 'string','default' =>[]],
                    ['name' => 'assignable_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'assignee_id ', 'type' => 'string','default' =>[]],
                    ['name' => 'assignee_group_id', 'type' => 'integer','default' =>[] ],
                    ['name' => 'status_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'created_at', 'type' => 'timestamp', 'default' => []],
                    ['name' => '    updated_at', 'type' => 'timestamp', 'default' => []],
                   
                );
                $this->createTable($assignable_logs_table_name,$assignable_logs_table_fields);


                $banner_table_name = strtolower($requestData['subdomain']).'_banner';
                $banner_table_fields = array(
                    ['name' => 'banner_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'type', 'type' => 'string','default' =>[]],
                    ['name' => 'click_url ', 'type' => 'string','default' =>[]],
                    ['name' => 'lanuguage_id', 'type' => 'integer','default' =>[] ],
                    ['name' => 'alt_text', 'type' => 'string', 'default' => []],
                    ['name' => 'image_code', 'type' => 'string', 'default' => []],
                    ['name' => 'custome_code', 'type' => 'string', 'default' => []],
                    ['name' => 'status', 'type' => 'integer', 'default' => []],
                   
                );
                $this->createTable($banner_table_name,$banner_table_fields);

                $categories_table_name = strtolower($requestData['subdomain']).'_categories';
                $categories_table_fields = array(
                    ['name' => 'category_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'description', 'type' => 'integer','default' =>[]],
                    ['name' => 'parent_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'assignee_id ', 'type' => 'string','default' =>[]],
                    ['name' => 'assignee_group_id', 'type' => 'integer','default' =>[] ],
                    ['name' => 'status_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'created_at', 'type' => 'timestamp', 'default' => []],
                    ['name' => '    updated_at', 'type' => 'timestamp', 'default' => []],
                   
                );
                $this->createTable($categories_table_name,$categories_table_fields);


                $countries_table_name = strtolower($requestData['subdomain']).'_countries';
                $countries_table_fields = array(
                    ['name' => 'country_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'country_name', 'type' => 'string','default' =>[]],
                    ['name' => 'iso_code_2', 'type' => 'string','default' =>[]],
                    ['name' => 'iso_code_3', 'type' => 'string','default' =>[]],
                    ['name' => 'format', 'type' => 'string','default' =>[] ],
                    ['name' => 'status', 'type' => 'integer', 'default' => []],
                    ['name' => 'priority', 'type' => 'integer', 'default' => []]
                                     
                );
                $this->createTable($countries_table_name,$contries_table_fields);


                $coupons_table_name = strtolower($requestData['subdomain']).'_coupons';
                $coupons_logs_table_fields = array(
                    ['name' => 'coupon_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'code', 'type' => 'string','default' =>[]],
                    ['name' => 'type', 'type' => 'string','default' =>[]],
                    ['name' => 'name', 'type' => 'string','default' =>[]],
                    ['name' => 'discount', 'type' => 'string','default' =>[]],
                    ['name' => 'min_total', 'type' => 'string','default' =>[]],
                    ['name' => 'redemptions', 'type' => 'integer','default' =>[]],
                    ['name' => 'customer_redemptions', 'type' => 'integer','default' =>[]],
                    ['name' => 'description', 'type' => 'string','default' =>[]],
                    ['name' => 'status', 'type' => 'integer','default' =>[]],
                    ['name' => 'date_added', 'type' => 'date','default' =>[]],
                    ['name' => 'validity', 'type' => 'integer','default' =>[]],
                    ['name' => 'fixed_date', 'type' => 'date','default' =>[]],
                    ['name' => 'fixed_from_time', 'type' => 'time','default' =>[] ],
                    ['name' => 'fixed_to_time', 'type' => 'time','default' =>[] ],
                    ['name' => 'period_start_date', 'type' => 'date','default' =>[]],
                    ['name' => 'period_end_date', 'type' => 'date','default' =>[]],
                    ['name' => 'recurring_every', 'type' => 'string','default' =>[]],
                    ['name' => 'recurring_from_time', 'type' => 'time', 'default' => []],
                    ['name' => 'recurring_to_time', 'type' => 'time', 'default' => []],
                    ['name' => 'order_restriction', 'type' => 'integer', 'default' => []]
                                     
                );
                $this->createTable($coupons_table_name,$coupons_table_fields);

                $coupons_history_table_name = strtolower($requestData['subdomain']).'_coupons_history';
                $coupons_history_table_fields = array(
                    ['name' => 'coupon_history_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'coupon_id ', 'type' => 'integer','default' =>[]],
                    ['name' => 'order_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'customer_id ', 'type' => 'integer','default' =>[]],

                    ['name' => 'code', 'type' => 'string','default' =>[]],
                    ['name' => 'min_total', 'type' => 'string','default' =>[]],
                    ['name' => 'amount', 'type' => 'string','default' =>[]],
                    ['name' => 'date_used', 'type' => 'datetime','default' =>[]],
                    ['name' => 'status', 'type' => 'integer','default' =>[]],
                   
                                     
                );
                $this->createTable($coupons_history_table_name,$coupons_history_table_fields);


                $currencies_table_name = strtolower($requestData['subdomain']).'_currencies';
                $currencies_table_fields = array(
                    ['name' => 'currency_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'country_id ', 'type' => 'integer','default' =>[]],
                    ['name' => 'currency_name', 'type' => 'string','default' =>[]],
                    ['name' => 'currency_code', 'type' => 'string','default' =>[]],
                    ['name' => 'currency_symbol', 'type' => 'string','default' =>[]],
                    ['name' => 'currency_rate', 'type' => 'string','default' =>[]],
                    ['name' => 'symbol_position', 'type' => 'string','default' =>[]],
                    ['name' => 'thousand_sign', 'type' => 'string','default' =>[]],
                    ['name' => 'decimal_sign', 'type' => 'string','default' =>[]],
                    ['name' => 'decimal_position', 'type' => 'string','default' =>[]],
                    ['name' => 'iso_alpha2', 'type' => 'datetime','default' =>[]],
                    ['name' => 'iso_alpha3', 'type' => 'string','default' =>[]],
                    ['name' => 'iso_alpha3', 'type' => 'string','default' =>[]],
                    ['name' => 'iso_numeric', 'type' => 'integer','default' =>[]],
                    ['name' => 'flag', 'type' => 'string','default' =>[]],
                    ['name' => 'currency_status', 'type' => 'integer','default' =>[]],
                    ['name' => 'date_modified', 'type' => 'datetime','default' =>[]],
                                                        
                );
                $this->createTable($currencies_table_name,$currencies_table_fields);


                $customers_table_name = strtolower($requestData['subdomain']).'_customers';
                $customers_table_fields = array(
                    ['name' => 'customer_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'first_name', 'type' => 'string','default' =>[]],
                    ['name' => 'last_name', 'type' => 'string','default' =>[]],
                    ['name' => 'email', 'type' => 'string','default' =>[]],
                    ['name' => 'password', 'type' => 'string','default' =>[]],
                    ['name' => 'telephone', 'type' => 'string','default' =>[]],
                    ['name' => 'address_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'newsletter', 'type' => 'string','default' =>[]],
                    ['name' => 'customer_group_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'ip_address', 'type' => 'integer','default' =>[]],
                    ['name' => 'date_added', 'type' => 'datetime','default' =>[]],
                    ['name' => 'status', 'type' => 'integer','default' =>[]],
                    ['name' => 'reset_code', 'type' => 'string','default' =>[]],
                    ['name' => 'reset_time', 'type' => 'string','default' =>[]],
                    ['name' => 'activation_code', 'type' => 'string','default' =>[]],
                    ['name' => 'is_activated ', 'type' => 'integer','default' =>[]],
                    ['name' => 'decimal_position', 'type' => 'string','default' =>[]],
                    ['name' => 'date_activated', 'type' => 'datetime','default' =>[]],
                    ['name' => 'last_login', 'type' => 'datetime','default' =>[]],
                    ['name' => 'last_seen', 'type' => 'datetime','default' =>[]],
                   
                                                        
                );
                $this->createTable($customers_table_name,$customers_table_fields);


                $customers_online_table_name = strtolower($requestData['subdomain']).'_customers_online';
                $customers_online_table_fields = array(
                    ['name' => 'activity_id', 'type' => 'integer', 'default' => []],
                    ['name' => 'customer_id', 'type' => 'integer','default' =>[]],
                    ['name' => 'last_name', 'type' => 'string','default' =>[]],
                    ['name' => 'access_type', 'type' => 'string','default' =>[]],
                    ['name' => 'browser', 'type' => 'string','default' =>[]],
                    ['name' => 'ip_address', 'type' => 'string','default' =>[]],
                    ['name' => 'country_code', 'type' => 'string','default' =>[]],
                    ['name' => 'request_uri', 'type' => 'string','default' =>[]],
                    ['name' => 'referrer_uri', 'type' => 'string','default' =>[]],
                    ['name' => 'date_added', 'type' => 'integer','default' =>[]],
                    ['name' => 'status', 'type' => 'integer','default' =>[]],
                    ['name' => 'user_agent', 'type' => 'string','default' =>[]]                                                          
                );
                $this->createTable($customers_online_table_name,$customers_online_table_fields);





                                                
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
                        'is_superadmin' => '0'
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