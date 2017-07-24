<?php
session_start();

$error="";

$emailtaken="";

$errorinlogin="";


    if(array_key_exists("logout", $_GET))

    {
      unset($_SESSION);

      setcookie("id","",time()-60*60);

      $_COOKIE="";

    }

    else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id']))

    {

      header("Location:session.php");

    }

    if(array_key_exists('submit', $_POST))

    { 
      
        $link = mysqli_connect("Server Add", "Server User", "pass", "user");

        if (mysqli_connect_error())

          {
            
            die ("There was an error connecting to the database");

            }


          if($_POST['email']=='')

          {
            $error="<p>Email field Missing</p><br>".$error;

          }

          if($_POST['password']=='')

          {

            $error="<p>Password Field Missing</p><br>".$error;

          }

          if($error!="")

          {

            $error = '<div class="alert alert-danger" role="alert"><p>There were error(s) in your form:</p>' . $error . '</div>';

          }

          else
          {

            if($_POST['signup']=='1')

            {


                $query="SELECT id FROM userdetail WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";

              $result=mysqli_query($link,$query);

              if(mysqli_num_rows($result)>0)

              {

                $emailtaken="The email-id is already taken,please log-in or click on forgot password in case you don't remember the password."  ;

              }

              if($emailtaken!="")

                {

                  $emailtaken = '<div class="alert alert-info" role="alert">' . $emailtaken . '</div>';

                }

              else

              {

                $query="INSERT into userdetail (email,password) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password'])."')";

                
                if(mysqli_query($link,$query))

                {

                  $query="UPDATE userdetail SET password='".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id=".mysqli_insert_id($link)."";

                  mysqli_query($link,$query);

                  $_SESSION['id']=mysqli_insert_id($link);

                  if(isset(($_POST['stayloggedin']))=='1')

                  {

                    setcookie("id",mysqli_insert_id($link),time()+60*60*24*365);

                  }

                  header("Location:session.php");

                }

                else

                {

                  echo"Try again Later";

                }

              }

          }

          else

          {
             $query = "SELECT * FROM userdetail WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";

            $result=mysqli_query($link,$query);

            $row=mysqli_fetch_array($result);

            if(isset($row))

              {

                  $hashedpassword=md5(md5($row['id']).$_POST['password']);


                  if($hashedpassword==$row['password'])

                  {

                    $_SESSION['id']==$row['id'];

                    if(isset(($_POST['stayloggedin']))=='1')

                    {

                      setcookie("id",$row['id'],time()+60*60*24*365);

                    }

                      header("Location:session.php");

                  }

                  else

                  {

                    $errorinlogin='<div class="alert alert-info" role="alert">'."Wrong Email/Password".'</div>';



                  }
              }

              else

              {

                $errorinlogin='<div class="alert alert-info" role="alert">'."Please Sign-Up First".'</div>';
              }

          } 
          }

    }


?>

<!DOCTYPE html>

<html>

<head>

 <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

  <title>Secret Diary</title>

  <style type="text/css">

    .container

    {

      padding: 50px;

      width: 500px;

      margin-top: 100px;

      text-align: center;

      
    }

    body

    {

              background:none;
      
    }

    html
    {
              background: url(secret.jpg) no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;
              min-height: 100%;
      
    }

    a
  {
    text-decoration: none;
    color: white;
  }
  a:hover
  {
    text-decoration: underline;
    color:green;
  }

   

    .spacing
    {
      margin-left: 80px;
    }
    .fight
      {
      text-align: center;
      }

     
        #loginform{
          display: none;
        }
        .jumbotron
        {
          min-width: 100%;
        }
  </style>
</head>

<body>


  <div class="container">

  <h1 class="display-4">Fight Club</h1>

  <div id="error"><? echo $error.$emailtaken.$errorinlogin; ?></div>

  <form method="post" id="signupform">

  <div class="form-group">

    <label for="email"></label>

    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter Email">

 </div> 
   <div class="form-group">

    <label for="exampleInputPassword1"></label>

    <input type="password" name="password" class="form-control" id="password" placeholder="Password">

  </div>

  <div class="form-check">

    <label class="form-check-label">

      <input type="checkbox" class="form-check-input" name="stayloggedin" value=1>

      Stay Logged-in

    </label>

  </div>

   <input type="hidden" name="signup" value="1">


  <button type="submit" class="btn btn-primary" id="submit" name="submit">Sign-Up!</button>
  
  <p><a href="#">Login</a></p>

</form>

<form method="post" id="loginform">

  <div class="form-group">

    <label for="email"></label>

    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter Email">

 </div>

   <div class="form-group">

    <label for="exampleInputPassword1"></label>

    <input type="password" name="password" class="form-control" id="password" placeholder="Password">

  </div>

  <div class="form-check">

    <label class="form-check-label">

      <input type="checkbox" class="form-check-input" name="stayloggedin" value=1>

      Stay Logged-in

    </label>

  </div>

   <input type="hidden" name="signup" value="0">



  <button type="submit" class="btn btn-success" id="submit" name="submit">Log-in</button>



</form>

</div>

 <div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-3">Fluid jumbotron</h1>
    <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script type="text/javascript">
  $()
</script>
</body>


</html></div>

  <div class="form-check">

    <label class="form-check-label">

      <input type="checkbox" class="form-check-input" name="stayloggedin" value=1>

      Stay Logged-in

    </label>

  </div>

   <input type="hidden" name="signup" value="1">


  <button type="submit" class="btn btn-primary" id="submit" name="submit">Sign-Up!</button>
  
  <p><a href="#" class="toggleforms">Login</a></p>

</form>

<form method="post" id="loginform">

  <div class="form-group">

    <label for="email"></label>

    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter Email">

 </div>

   <div class="form-group">

    <label for="exampleInputPassword1"></label>

    <input type="password" name="password" class="form-control" id="password" placeholder="Password">

  </div>

  <div class="form-check">

    <label class="form-check-label">

      <input type="checkbox" class="form-check-input" name="stayloggedin" value=1>

      Stay Logged-in

    </label>

  </div>

   <input type="hidden" name="signup" value="0">



  <button type="submit" class="btn btn-success" id="submit" name="submit">Log-in</button>

  <p><a href="#" class="toggleforms">Sign-Up!</a></p>

</form>

  </div>
   <footer class="footer">
      
        <h2>footer of my Site</h2>
      
    </footer>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(".toggleforms").click(function(){
    
    $("#loginform").toggle();
    $("#signupform").toggle();
    $("#error").hide();
  });
</script>
</body>


</html>