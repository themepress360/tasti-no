<!DOCTYPE html>
<html>
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script type="text/javascript">
         var base_url = '<?php echo base_url(); ?>/';
         var access_token = '<?php echo csrf_token(); ?>'
      </script>
      <script>
         window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            ]); ?>
      </script>
      <link rel="stylesheet" href="http://tasti.no/style.css" >
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
      <script src="{{asset('admin/js/jquery.min.js')}}"></script>
      <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
      <link rel="stylesheet" type="text/css" href="https://dev-tollpays.amaxzatech.com/public/administration/default/css/toastr.min.css" />
      <script src="https://dev-tollpays.amaxzatech.com/public/administration/default/js/toastr.min.js"></script> 
      <script src="{{asset('admin/js/common.js')}}"></script>
   </head>
   <body>
      <form id="SubAdminLoginForm">
         <div class="content">
            <h1 class="text-color">www.restaurantsoftware.com</h1>
            <p class="text-color p-font m-t-50 m-b-50">Resturant Owners Login/Admin Login Page</p>
            <div class="m-b-50">
               <?php $subdomain = (explode('.', $_SERVER['HTTP_HOST'])[0]);?>
               <input type="hidden" class="m-b-20" placeholder="Subdomain" name="subdomain" value="<?php echo $subdomain;?>" /> 
               <input class="m-b-20" type="email" placeholder="Email" name="email"/>
               <input class="m-b-20" type="password" placeholder="Password" name="password" />
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
            <button type="button" class="btn p-font btn-border ajax svBtn" method="subdomainuserlogin" validate="1" callback="subadminloginCallback" formid="SubAdminLoginForm" style="background-color:#2BAD44;color:#fff">Log In</button> 
         </div>
      </form>
      <script type="text/javascript">
         function subadminloginCallback(response)
         {
          toastr['success']("Login successfully.");
            window.location = base_url + 'successlogin/' +response.data.restaurant_id; 
         }
      </script>
   </body>
</html>