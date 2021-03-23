<?php

function autoloader($class)
{
    include '../' . $class . '.php';
}
spl_autoload_register('autoloader');
$user = new classes\user();

if (!$user->is_logged()) {
    header('Location: login.php');
}
$where_add = '';
if ((isset($_GET['group']) || isset($_GET['person'])) && count($_GET) == 1) {

    if (isset($_GET['group'])) {
        $groud_id = $_GET['group'];
        $this_group = new classes\group();
        $this_group->group_id($groud_id);
        $member_of_this_group = $this_group->whether_group_member();
        if($member_of_this_group==false){
            header('Location: group-list.php');
            exit();
        }
    }
} else {
    header('Location: group-list.php');
    exit();
}

$join_file = true;
$error_file = '';
$error_content = '';
$new_name = '';
$add_the_picture_fail = false;
$add_the_picture = false;
if (isset($_POST['submit']) && isset($_FILES["fileToUpload"]["name"]) && !empty($_FILES["fileToUpload"]["name"])) {
    $add_the_picture_fail = false;
    $add_the_picture = true;
    $target_dir = "posts_img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {

        $uploadOk = 1;
    } else {
        $error_file = "File is not an image.";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size                      
    if ($_FILES["fileToUpload"]["size"] > 5242880) {
        $error_file = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    ) {
        $error_file = "Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }
}

if (isset($_POST['title']) && isset($_POST['content'])) {

    $content = $_POST['content'];
    $_SESSION['post_content'] = $_POST['content'];
    $add_post = true;
    if ($_POST['title'] != '') {
        $title = $_POST['title'];
        $_SESSION['post_title'] = $_POST['title'];
    } else {
        $error_content = "This field cannot be empty!";
        $add_post = false;
    }




    if ($add_post == true && $add_the_picture_fail == false) {
        if ($add_the_picture == true) {
            if ($uploadOk == 0) {
                $add_the_picture_fail = true;
            } else {
                $new_name = generateRandomString().'.'. $imageFileType;;
                $target_file = $target_dir . $new_name;
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                }
            }
        }
        if (isset($_GET['group'])) {
            $where_add = 'group';
            $where_id = $_GET['group'];
            $next = 'group';
        }
        $new_post = new classes\post();
        $new_post -> add_post($title,$content,$new_name,$where_add,$where_id);
        // add_post
        header('Location: post.php?post='.$where_id);
        unset($_SESSION['post_title']);
        unset($_SESSION['post_content']);
       
    }
}

function generateRandomString($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
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
    <title>Add new post</title>
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
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                    <p>Places to add: Your profile</p>
                    <div class="mb-3">
                        <label for="post_title" class="form-label">Title</label>
                        <?php if ($error_content != '') {
                            echo '<p style="color:red;">' . $error_content . '</p>';
                        } ?>
                        <input type="text" class="form-control" name="title" id="post_title" value="<?php if (isset($_SESSION['post_title'])) echo $_SESSION['post_title']; ?>" required>

                    </div>
                    <div class="mb-3">


                        <label for="post_content" class="form-label">Content</label>
                        <textarea class="form-control" id="post_content" name="content" rows="3"><?php if (isset($_SESSION['post_content'])) echo $_SESSION['post_content']; ?></textarea>
                        <div id="post_contentHelp" class="form-text">(This field is not required)</div>
                    </div>
                    <div class="mb-3">

                        <label for="formFile" class="form-label">Add image</label>
                        <?php if ($error_file != '') {
                            echo '<p style="color:red;">' . $error_file . '</p>';
                        } ?>
                        <input name="fileToUpload" class="form-control" type="file" id="formFile">
                        <div id="formHelp" class="form-text">The maximum image size is 5MB (This field is not required)</div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Add post</button>
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
<?php
unset($_SESSION['post_title']);
unset($_SESSION['post_content']);

?>

</html>