<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{asset('style.css')}}" >

</head>
<body>

<div class="content">
  <h1 class="text-color">www.restaurantsoftware.com</h1> 
      
   <p class="text-color p-font m-t-50 m-b-50">Super Admin Login page</p> 

  
   <form action="/login" method="POST">
   	@csrf

   <div class="m-b-50">
     <input class="m-b-20" placeholder="Email" name="email"/>
                
                  <li class = "text-danger" style="list-style: none;color:red"> {{ session()->get('email_error') }}</li>
                

   <input class="m-b-20" placeholder="Password" name="password" />
    
                  <li class = "text-danger" style="list-style: none;color:red">{{ session()->get('password_error') }}</li>
               
  
 </div>


      <a href="/all-subdomains"><button class="btn p-font btn-border">Log In</button></a>
</div>

</form>




</body>
</html>