<?php
// session_unset();
ob_start();

function autoloader($class) {
  include '../' . $class . '.php';
}
spl_autoload_register('autoloader');

$user = new classes\user();

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $user->login($login, $password);
}
if ($user->is_logged()) {
    header('Location: index.php');
}else{

}

ob_end_flush();
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link href="css/all.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <title>Login</title>
</head>
<style>
  html,
  body {
    height: 100%;
  }

  body {
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;

    background-image: url('img/login.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }

  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }

  .form-signin .checkbox {
    font-weight: 400;
  }

  .form-signin .form-control {
    position: relative;
    box-sizing: border-box;
    height: auto;
    padding: 10px;
    font-size: 16px;
  }

  .form-signin .form-control:focus {
    z-index: 2;
  }

  .form-signin input[type="Login"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

  form {
    color:#996600;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;

  }
  .btn-secondary{
    background-color: #996600;
    border-color: #996600;
  }
  .btn-secondary:hover{
    background-color: #805500;
    border-color: #805500;
  }
</style>

<body class="text-center">
  <main class="form-signin">
  <form action="<?php echo $_SERVER['REQUEST_URI']; ?> " method="POST">

      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
      <?php echo $user->display_errors(); ?>
      <label for="inputLogin" class="visually-hidden">Login</label>
      <input type="Login" name="login" id="inputLogin" class="form-control" placeholder="Login" required="" autofocus="">
      <label for="inputPassword" class="visually-hidden">Password</label>
      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="">
     
      <button class="w-100 btn btn btn-secondary" type="submit">Sign in</button>
      <a href="" class="w-75 btn btn btn-success mt-1">Create a new account</a>
      <p class="mt-5 mb-3">©2021</p>
    </form>
  </main>


  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    -->
</body>

</html>