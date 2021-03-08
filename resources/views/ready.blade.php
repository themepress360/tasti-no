<!DOCTYPE html>
<html>
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="http://tasti.no/style.css" >
   </head>
   <body>
      <div class="content">
         <h1 class="text-color"><?php echo base_url(); ?></h1>
        
        <div>
         <h2>Dear [First Name] </h2>
        </div>
        
         <div class="text-color m-t-50">
            <h1>Your Landing Page is ready to use!</h1>
         </div>
         <div class="text-color m-t-50 m-b-20">
            <h2>Subdomain URL </h2>
         </div>
         <div class="text-color m-t-20">
            <p class="p-font-1"><?php echo $is_subdomain_exists['subdomain']; ?><?php echo '.'.$_SERVER['SERVER_NAME'];?></p>
         </div>
         
         <!-- <a  class="btn p-font btn-border" href="http://<?php echo $is_subdomain_exists['subdomain']; ?><?php echo '.'.$_SERVER['SERVER_NAME'];?>/subadmin">Log in to backend</a> -->
         <button onclick="loginpage('http://<?php echo $is_subdomain_exists['subdomain']; ?><?php echo '.'.$_SERVER['SERVER_NAME'];?>/subadmin');" class="btn p-font btn-border" >Click Here to Visit your Login Page</button>
      </div>
   </body>
   <script type="text/javascript">
      function loginpage(link)
      {
         window.location = link; 
         
      }
   </script>
</html>