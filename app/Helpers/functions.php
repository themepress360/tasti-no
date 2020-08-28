<?php

function sortByOrder($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
}

function getdownload($filePath)
{
    if(file_exists($filePath)) {
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);
        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: ".$fileSize);
        header("Content-Disposition: attachment; filename=".$fileName);
        // Output file.
        readfile($filePath);                   
        return true;
    }
    else {
        return false;
        //die('The provided file path is not valid.');
    }
}
function buildTree( $array, $parentId = 0) {
    // print_r($array); exit;
     $branch = array(); 
    foreach ($array as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = buildTree($array, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            else
            {
                $element['children'] = [];
            }
            $branch[] = $element;
        }
    }
    return $branch;
}
function generate_timezone_list() 
{
	$zones_array = array();
	$timestamp = time();
	foreach(timezone_identifiers_list() as $key => $zone) {
	  date_default_timezone_set($zone);
	  $zones_array[$key]['zone'] = $zone;
	  $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
      
      $dateTime = new DateTime();
      $dateTime->setTimeZone(new DateTimeZone($zone));
      $zones_array[$key]['abb'] = $dateTime->format('T');
      
    }
	return $zones_array;
}
function generate_abbreviation_full_name($abb)
{
    if(strtoupper($abb) == "PKT")
        return "Pakistan Standard Time";
    elseif(strtoupper($abb) == "PST")
        return "Pacific Standard Time";
    elseif(strtoupper($abb) == "EST")
        return "Eastern Standard Time";
    else
        return "";
}

function generate_timezone_abb($time_zone) 
{
	$zones_array = array();
	$timestamp = time();
	date_default_timezone_set($time_zone);
	$zones_array['zone'] = $time_zone;
	$zones_array['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);  
    $dateTime = new DateTime();
    $dateTime->setTimeZone(new DateTimeZone($time_zone));
    $zones_array['abb'] = $dateTime->format('T');
	return $zones_array;
}
function random_number($id,$lenght) 
{
    $id_length = strlen($id);
    $num_lenght = $lenght - $id_length;
    $random_Number = substr(str_shuffle("0123456789"), 0, $num_lenght);
    return $id.$random_Number;
}

function time_ago($given_date)
{
    $match_date = strtotime($given_date);
    $date = strtotime(date("Y-m-d H:i:s"));
    $difference = $date - $match_date;
    if($difference < 86400)
        return "Today,".date("H:i:s A",$match_date);
    else if ($difference < 172800)
        return "Yesterday,".date("H:i:s A",$match_date);
    else
        return date("Y-m-d,H:i:s A",$match_date);
}
function number_format_short( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}
function human_number($number) {
    $number = preg_replace('/[^\d]+/', '', $number);
    if (!is_numeric($number)) {
        return 0;
    }
    if ($number < 1000) {
        return $number;
    }
    $unit = intval(log($number, 1000));
    $units = ['', 'K', 'M', 'B', 'T', 'Q'];
    if (array_key_exists($unit, $units)) {
        return sprintf('%s%s', rtrim(number_format($number / pow(1000, $unit), 1), '.0'), $units[$unit]);
    }
    return $number;
}
function getIp()
{
	$ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
	return $ip;
}
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function image_url($folder,$file_name)
{   
    $file_name = trim($file_name)=='' || !file_exists(storage_path(DefaultStorage.'/'.$folder.'/'.$file_name)) ? 'noimage.png' : $file_name;
    return url("storage/app/".$folder.'/'.$file_name);
}

function report_url($folder,$file_name)
{   
    $file_name = trim($file_name)=='' || !file_exists(storage_path(DefaultStorage.'/'.$folder.'/'.$file_name)) ? '' : $file_name;
    if(!empty($file_name))
        return url("storage/app/".$folder.'/'.$file_name);
    else
        return  "";
}

function image_path($folder,$file_name)
{
   
	$file_name = trim($file_name)=='' || !file_exists(storage_path(DefaultStorage.'/'.$folder.'/'.$file_name)) ? 'noimage.png' : $file_name;
	return storage_path(DefaultStorage.'/'.$folder.'/'.$file_name);
}

function base_url($var="")
{
	return url($var);
}

function root_path($var="")
{
	return base_path($var);
}

function cdn_url($var="")
{
	return url($var);
}
function  secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    return $obj;
}
function is_valid_youtube_link($url="")
{
    $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/";

    if(!preg_match($regex_pattern, $url, $match)){      
        return false;
    } else {
        if(empty($match[4]))
            return false;
        else
        {
            $hit_link = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=id&id={$match[4]}&key=AIzaSyDtSNukiq0KEHTzyo3XYdzeaWQnGiS3Rww"),1);            
            if(empty($hit_link['items']))
                return false;
        }
    } 

    return true;
}
function SendGridMail($to,$subject,$body)
{   
    $url = 'https://api.sendgrid.com/';
	$json_string = ['to' => [$to, $to],'category' => 'website'];
	$params = array(
		'api_user'  => "fibg",
		'api_key'   => "Propane2016.",
		'x-smtpapi' => json_encode($json_string),
		'to'        => $to,
		'subject'   => $subject,
		'html'      => $body,
		'text'      => $body,
		'from'      => SUPPORT_EMAIL,
	  );
	$request =  $url.'api/mail.send.json';
	$response =  CURL($request,$params);
    return true;
}
function CURL($url,$var=''){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST ,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS ,$var);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER ,0); // DO NOT RETURN HTTP HEADERS
    curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); // RETURN THE CONTENTS OF THE CALL;
            
	return curl_exec($ch);
}
function rdd($arr)
{
	echo '<textarea style="width:100%;height:800px" width="100%" readonly height="800">';
	print_r($arr);
	echo '</textarea>';	
}
function code_to_country( $code ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }
