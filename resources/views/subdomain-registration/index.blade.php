<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="{{asset('style.css')}}" >
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
  .input-subdomain{
  border: 1px solid #222;
  
    width: 235px;
    margin-left: 84px;
}
</style>
</head>
<body>

<div class="content">
  <h1 class="text-color">www.restaurantsoftware.com</h1> 
      
   <p class="text-color p-font m-t-50 m-b-50">Create your Website !</p> 
   <div class="m-b-50">
   <input class="m-b-20" placeholder="Restaurant Name" name="restaurant-name" />
   <div class="input-group"> 
   <input class="m-b-20 input-subdomain" placeholder="Subdomain" name="subdomain" />
   <div class="input-group-append" style="height:54px;">
    <span class="input-group-text" id="basic-addon2"> {{$_SERVER['SERVER_NAME'] }} </span>
  </div>
</div>
   <input class="m-b-20" placeholder="Email" name="email"/>
   <input class="m-b-20" placeholder="Password" name="phone" />
   <input class="m-b-20" placeholder="Confirm Password" name="phone" />

 </div>

     
      <a href="/ready"><button class="btn p-font btn-border" style="background-color:#2BAD44;color:#fff">Make Site !</button></a>
</div>

 

</body>
</html>