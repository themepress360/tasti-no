<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Validator;
use App\Traits\ValidationTrait as ValidationTrait;
use App\Restaurants as Restaurants;
use App\User as User;
use DB;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.login');
    }

    public function SuperAdminLogin(Request $request)
    {
      
    	 $this->validate($request, [
       		'email' => 'required|email',
            'password' => 'required|min:6'
    	]);

         $super_admin = User::where(["is_superadmin" => '1'])->first();
         if ($request->email != $super_admin->email){

           
            return redirect()->back()->with('email_error', 'Email Does Not Match');
         } 

         else if($request->password != $super_admin->password){

             return redirect()->back()->with('password_error', 'Password Does Not Match');
         }
            
      else
          if($request->email == $super_admin->email && $request->password == $super_admin->password){

	    	//return view('super-admin.list-all-subdomains');
            return redirect('all-subdomains');
	  	
	  }
    
    //  else
    //  {
    //    Redirect::to('/super-admin');
        // print_r($request->email."|".$super_admin->email);
        // print_r("<br>");
        // print_r($request->password."|".$super_admin->password);
        // exit();
        // if($request->email != $super_admin->email && $request->password != $super_admin->password)
        // {
        //     $data['email_error'] = "Email is incorrect";
        //     $data['password_error'] = "Password is incorrect";
               
        // }
        // else if($request->email != $super_admin->email && $request->password == $super_admin->password)
        // {
        //     $data['email_error'] = "Email is incorrect";
        //     $data['password_error'] = "";
        // }
        // else
        // {
        //     $data['email_error'] = "";
        //     $data['password_error'] = "Password is incorrect";
        // }
        // // print_r("<pre>");
        // // print_r($data);
        // // exit();
        // return view('super-admin.login',$data);
    //  }
	  

    }

    	public function ShowAllSubDomains(){

    			
    			$all_subdomains = Restaurants::join('users', 'users.name', '=' , 'restaurants.name')->get();
    		//	dd($all_subdomains);
    			$subdomain_count = count($all_subdomains);
                //dd($subdomain_count);
    			
    		return view('super-admin.list-all-subdomains', compact(['all_subdomains', 'subdomain_count']) );
    	}

   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
