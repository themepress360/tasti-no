<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use File;
use Input;
use Location;
use Storage;
use \Cache;
use \Carbon\Carbon as Carbon;

abstract class CommonModel extends Model
{

    public static function get($id)
    {
        $data = Cache::get(static::$model . '-' . $id);
        
        if (empty($data)) {
            $dataObject = static::where(['id' => $id])->get()->first();
            
            if ($dataObject) {
                Cache::forever(static::$model . '-' . $id, $dataObject->toArray());
                $data = $dataObject->toArray();
            } else {
                $data = false;
            }

        }
        
        if (isset($data['created_at'])) {
            $userLocation               = static::userLocation();
            $data['created_timestamp']  = empty($data['created_at']) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $data['created_at'], 'UTC')->setTimezone($userLocation->time_zone)->format('Y-m-d H:i:s');
            $data['modified_timestamp'] = empty($data['updated_at']) ? '' : Carbon::createFromFormat('Y-m-d H:i:s', $data['updated_at'], 'UTC')->setTimezone($userLocation->time_zone)->format('Y-m-d H:i:s');
        }
        unset($data['created_at']);
        unset($data['updated_at']);

        return $data;
    }

    public static function dateToUTC($localTime, $timezone = '')
    {
        if ($timezone == '') {
            $userLocation = static::userLocation();
            $timezone     = $userLocation->time_zone;
        }
        $dateInLocal = new Carbon($localTime, $timezone);
        return $dateInLocal->tz('utc')->format('Y-m-d H:i:s');
    }

    public static function UTCToDate($localTime, $timezone = '')
    {
        if ($timezone == '') {
            $userLocation = static::userLocation();
            $timezone     = $userLocation->time_zone;
        }
        return $data['modified_timestamp'] = Carbon::createFromFormat('Y-m-d H:i:s', $localTime, 'UTC')->setTimezone($timezone)->format('Y-m-d H:i:s');
    }

    public static function userLocation()
    {
        $userLocation = session()->get('userLocation');

        if (!$userLocation) {
            $userLocation = static::getLocation($_SERVER["REMOTE_ADDR"] == "::1" ? '103.24.99.166' : $_SERVER["REMOTE_ADDR"]);
            session()->put('userLocation', $userLocation);
            $userLocation = session()->get('userLocation');
        }
		elseif($userLocation->time_zone=='')
		{
            $userLocation = static::getLocation('103.24.99.166');
            session()->put('userLocation', $userLocation);
            $userLocation = session()->get('userLocation');			
		}

        return $userLocation;
    }

    public static function getAll($search = array(), $limit = PAGINATOR_LIMIT, $orderBy = false , $joins=array())
    {
    	$data['data'] = [];
    	$query = static::select(static::$model.'.id');
              
        foreach ( $joins as $index => $table) {
            if(is_array($table))
                $query->LeftJoin($table['table'], $table['from'].'_id', '=',$table['table'].'.id');
            else
                $query->LeftJoin($table, static::$model.'_id', '=', static::$model.'.id');
        }

        if(count($joins)>0)
            $query->groupby(static::$model.'.id');
                
        $query->where($search);

        if ($orderBy) {
            $query = $query->orderByRaw($orderBy);
        }

        if($limit<1)
        {
            $limit = 999;
            // set the current page
            \Illuminate\Pagination\Paginator::currentPageResolver(function () {
                return 1;
            });
        } else {
             // set the current page
            \Illuminate\Pagination\Paginator::currentPageResolver(function () {
                return !empty(Input::get('page')) ? Input::get('page') : '1';
            });           
        }

        $paginate = $query->paginate($limit);
        
        foreach ($paginate as $key => $row) {
            $data['data'][] = static::details($row->id);           
        }
        $pageData = $paginate->toArray();

        $data['pagination'] = array();

        // calculate next record
        if ($pageData['current_page'] < $pageData['last_page']) {
            $next = $pageData['current_page'] + 1;
        } else {
            $next = null;
        }

        // calculate previous record
        if ($pageData['current_page'] > 1) {
            $previous = $pageData['current_page'] - 1;
        } else {
            $previous = 1;
        }

        $data['pagination']['next']     = /*$next*/$pageData['current_page'] + 1;
        $data['pagination']['previous'] = $previous;
        $data['pagination']['current']  = $pageData['current_page'];
        $data['pagination']['first']    = 1;
        $data['pagination']['perpage']  = $pageData['per_page'];
        $data['pagination']['last']     = $pageData['last_page'];
        $data['pagination']['to']       = $pageData['to'];
        $data['pagination']['from']     = $pageData['from'];
        $data['pagination']['total']    = ceil($pageData['total']/$pageData['per_page']);
        $data['pagination']['totalRecords']    = $pageData['total'];
        
        // return data and 200 response
        return $data;
    }

    public static function add($data)
    {
        return static::create($data);
    }

    public static function updateRecord($search = array(), $data = array())
    {
        $records = static::where($search)->get();

        foreach ($records as $index => $row) {
            if ($row->update($data)) {
                Cache::forget(static::$model . '-' . $row->id);
            }
        }
        return true;
    }

    public static function FindFirst($search = array())
    {
        $response = static::select('id')->where($search)->limit(1)->first();

        if ($response) {
            return static::details($response->id);
        } else {
            return false;
        }

    }

    public static function uploadImage($path, $file, $width = 1020)
    {
        $filename = '';
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $filename  = uniqid() . '.' . $extension;
            Storage::put($path . '/' . $filename, File::get($file));

            $image = Image::make(Storage::get($path . '/' . $filename))
                ->resize($width, null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    })
                ->stream();
        Storage::put($path . '/' . $filename, $image);
        }
        return $filename;
    }


    public static function uploadImageByString($path,$image_data, $width = 1020)
    {       
        $filename = '';
        if (!empty(trim($image_data))) {          
            $filename  = uniqid() . '.' . 'jpg';
            Storage::put($path . '/' . $filename, base64_decode(str_replace(' ', '+',$image_data)));

            $image = Image::make(Storage::get($path . '/' . $filename))
                ->resize($width, null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    })
                ->stream();
        Storage::put($path . '/' . $filename, $image);            
        }
        return $filename;
    }

    public static function remove($search = array())
    {
        $data = static::select('id')->where($search)->get();

        foreach ($data as $index => $row) {
            if ($row->delete()) {
                Cache::forget(static::$model . '-' . $row->id);
            }
        }
        return true;
    }

    public static function getLocation($ip)
    {
        $data     = Location::get($ip);
        $timeZone = json_decode(
            CURL('https://maps.googleapis.com/maps/api/timezone/json?location=' . $data->latitude . ',' . $data->longitude .'&timestamp=1331161200&key=' . GOOGLE_TIMEZONE_KEY)
        );
        $data->time_zone = $timeZone->status == 'OK' ? $timeZone->timeZoneId : '';
        return $data;
    }

    public static function getLatLongOfLocation($addresString)
    {
        $address = json_decode(
            CURL('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($addresString) . '&key=' . GOOGLE_TIMEZONE_KEY)
        );

        if (!empty($address->results[0])) {
            return [
                'latitude'  => $address->results[0]->geometry->location->lat,
                'longitude' => $address->results[0]->geometry->location->lng,
            ];
        } else {
            return [
                'latitude'  => '',
                'longitude' => '',
            ];
        }
    }


    static function sendNotificationToAllUsers($customer_id,$subject,$message)
    {
        $return = [];
        $users = CustomerUsers::getAll(['customer_id'=>$customer_id,'user_status'=>'1','user_deleted'=>'0'],0);

        foreach ($users['data'] as $key => $user) {
            $where = [
                    ['uid','=',$user['id']],
                    ['device_type','!=','web'],
                    ['device_id','!=','-'],
                    ['user_type','=','customer']
                ];
            $access_token = Accesstokens::where($where)->get()->toArray();

            if(!empty($access_token))
            {

            	foreach ($access_token as $token) {
	                if($token['device_type']=='android' || true)
	                {
	                    $return[] = send_notification_GCM_android($token['user_type'],$token['device_id'],$message,$subject);   
	                }
	                elseif($token['device_type']=='iphone')
	                {
	                    $return[] = send_notification_iphone($token['user_type'],$token['device_id'],$message,$subject);
	                }
            	}

            }
            SendGridMail($user['user_email'],$subject,'Hi '.ucfirst($user['user_first_name']).',<br><br> '.$message.'<br><br>
                         You can also contact us by phone call : '.SUPPORT_PHONE.' or by email : '.SUPPORT_EMAIL.' for any help 24x7                                
                        <br><br>Thanks,<br>Quality Anesthesia Team.',"Content-Type: text/html; charset=ISO-8859-1\r\n");            
        }

        return $return;
    }


    static function sendNotificationToCrna($crna_id,$subject,$message)
    {
        $return = [];
        $crnas = Crna::where(['id'=>$crna_id,'crna_status'=>'1','crna_deleted'=>'0'])->get();

        foreach ($crnas as $key => $crna) {
            $where = [
                    ['uid','=',$crna['id']],
                    ['device_type','!=','web'],
                    ['device_id','!=','-'],
                    ['user_type','=','crna']
                ];
            $access_token = Accesstokens::where($where)->get()->toArray();

            if(!empty($access_token))
            {
            	foreach ($access_token as $token) {
	                if($token['device_type']=='android' || true)
	                {
	                    $return[] = send_notification_GCM_android($token['user_type'],$token['device_id'],$message,$subject);   
	                }
	                elseif($token['device_type']=='iphone')
	                {
	                    $return[] = send_notification_iphone($token['user_type'],$token['device_id'],$message,$subject);
	                }
            	}
            }    
            
            SendGridMail($crna['crna_email'],$subject,'Hi '.ucfirst($crna['crna_first_name']).',<br><br> '.$message.'<br><br>
                         You can also contact us by phone call : '.SUPPORT_PHONE.' or by email : '.SUPPORT_EMAIL.' for any help 24x7                                
                        <br><br>Thanks,<br>Quality Anesthesia Team.',"Content-Type: text/html; charset=ISO-8859-1\r\n");                        
        }

        return $return;
    }



}
