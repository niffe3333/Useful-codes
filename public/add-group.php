<?php

function autoloader($class) {
    include '../' . $class . '.php';
  }
  spl_autoload_register('autoloader');
$user = new classes\user();

if(!$user->is_logged()){
    header('Location: login.php');
}
$join_file = true;


if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['tags'])) {
  
    $new_group = new classes\group();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $new_group -> add_group($title, $description, $tags);

    
}



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
    <link href="css/posts.css" rel="stylesheet">
    <link href="css/menuNav.css" rel="stylesheet">
    <link href="css/navbarRight.css" rel="stylesheet">
    <link href="css/navbarLeft.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Add new group</title>
</head>

<body>

    <!-- NAVBAR -->
    <?php require_once 'header.php'; ?>
    <!-- END NAVBAR -->
    <!-- RIGHT BAR -->


    <?php require_once 'right_bar.php'; ?>
    <!-- END RIGHT BAR -->

    <!-- LEFT BAR -->
    <?php require_once 'left_bar.php'; ?>


    <!-- END LEFT BAR -->

    <!-- CENTER -->

    <div class="container">

        <div class="row d-flex justify-content-center mt-5">

            <div class="col-md-8">
            <form action="<?php echo $_SERVER['REQUEST_URI']; ?> " method="POST">
                    <p>Places to add: Your group</p>
                    <div class="mb-3">
                        <label for="group_title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="group_title" minlength="1" maxlength="100" required>
                        <div id="group_titleHelp" class="form-text">(This field is required)</div>
                    </div>
                    <div class="mb-3">
                        <label for="group_description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="group_content" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="group_tags" class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" id="group_tags">
                        <div id="group_tagsHelp" class="form-text">(You can add tags to make it easier to find a group)</div>
                    </div>
                    <button type="submit" class="btn btn-success">Add group</button>
                </form>

            </div>
        </div>

    </div>

    <!-- CENTER -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
    -->
</body>

</html>