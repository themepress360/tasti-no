<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{asset('style.css')}}" >


</head>
<body>

<div class="content">
  <h1 class="text-color">www.restaurantsoftware.com</h1> 
      
   <p class="text-color p-font m-t-50 m-b-50">Total No of Subdomains Registerd are:  </p> 
  
  <table>
  <tr>
    <th>Restuarant Name</th>
    <th>User Name</th>
    <th>subdomain</th>
    <th>Date Created</th>
  </tr>
  <tr>
    @foreach($all_subdomains as $subdomains)
    <td>{{$subdomains->name}}</td>
     <td>{{$subdomains->email}}</td>
     <td>{{$subdomains->subdomain}}.@php echo $_SERVER['SERVER_NAME'] @endphp</td>
     <td>{{$subdomains->created_at}}</td>
  </tr>
  @endforeach
  
</table>

     
     
</div>

 

</body>
</html>