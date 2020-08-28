<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{

	protected $template_folder = '';	
	
	public function __construct()
	{
        
	}

	protected function view($template,$vars=array())
	{
		$arr = array_merge($vars,
			array(
				'view'=>$this->template_folder.'.',
				'public'=>'/public'.$this->template_folder.'/',
				'public_dir'=>root_path("public").$this->template_folder.'/',
				)
			);
		return view($this->template_folder.'.'.$template,$arr)->render();
	}

	public function response($data,$status)
	{
		if(empty($data['message']))
		{
			$data['message'] = 'ok';
		}
		
		$data = array_merge(
				[
					"program" => APPLICATION_NAME,
					"release" => API_VERSION,
					"code" => $status,
					"message" => $data['message']
				],
			$data
		);
		array_walk_recursive($data, function(&$item){if(is_numeric($item) || is_float($item)){$item=(string)$item;}});
		return \Response::json($data,200);
	}	
}