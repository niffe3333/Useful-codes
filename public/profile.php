<?php
ob_start();
function autoloader($class)
{
    include '../' . $class . '.php';
}
spl_autoload_register('autoloader');
$user = new classes\user();
if (!$user->is_logged()) {
    header('Location: login.php');
}
$join_file = true;


if (isset($_GET['person'])) {
    $this_user = new classes\user();
    $this_user->user_id($_GET['person']);
    $this_user_info = $this_user->user_info();
    $profile_owner = $this_user->account_owner();

    if (isset($_POST['remove'])) {
        $this_user->remove_friendship();
    }
    if (isset($_POST['accept'])) {
        $this_user->invitation_accept();
    }
  
    $friend_ship = $this_user->user_friendship();
    if (isset($_POST['add'])) {
        $this_user->send_invitation();
    }
   
  
    $invitation_sent = $this_user->invitation_sent();
    $invitation_get = $this_user->invitation_get();
    
} else {
    header('Location: index.php');
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
    <link href="css/posts.css" rel="stylesheet">
    <link href="css/menuNav.css" rel="stylesheet">
    <link href="css/navbarRight.css" rel="stylesheet">
    <link href="css/navbarLeft.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title><?php echo $this_user_info['first_name'] . ' ' . $this_user_info['last_name']; ?></title>
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
    <!-- USER INFO -->
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <h1><?php echo $this_user_info['first_name'] . ' ' . $this_user_info['last_name']; ?></h1>

            </div>
        </div>
        <?php if ($profile_owner == false) : ?>
            <div class="container">
                <div class="row d-flex justify-content-center mt-5">
                    <div class="col-md-6 text-center">
                        <?php if ($this_user_info['about_me'] != '') echo '<p>' . $this_user_info['about_me'] . '</p>'; ?>
                        <p>
                            <?php if ($friend_ship == false && $invitation_sent == false && $invitation_get == false) : ?>
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="add">
                            <button class="btn btn-success"> Add friends <i class="fas fa-plus"></i></button>
                        </form>
                    <?php elseif ( $invitation_sent == true) : ?>
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="remove">
                            <button class="btn btn-secondary">Remove the invitation <i class="fas fa-minus"></i></button>
                        </form>
                    <?php elseif ( $invitation_get == true) : ?>
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="accept">
                            <button class="btn btn-secondary">Accept <i class="fas fa-plus"></i></button>
                        </form>
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="remove">
                            <button class="btn btn-secondary">Remove the invitation <i class="fas fa-minus"></i></button>
                        </form>
                    <?php elseif ( $friend_ship == true) : ?>
                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="remove">
                            <button class="btn btn-secondary">Remove friend <i class="fas fa-minus"></i></button>
                        </form>

                    <?php endif; ?>

                    <a href="" class="btn btn-success"> Write a message <i class="fas fa-comment-dots"></i></a>
                    </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- END USER INFO -->
        <!-- NEW POST -->
        <div class="container">
            <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-6 text-center">
                    <a href="add-post.php?group=<?php echo $this_group_info['id']; ?>" class="btn btn-success"> <?php echo $invitation_get;?> Add new post here <i class="fas fa-plus"></i></a>
                </div>

            </div>
        </div>

        <!-- END NEW POST -->
        <!-- CENTER -->

        <div class="container" id="user_posts">


        </div>
        <!-- SHOW MORE -->
        <!-- <div class="container">
            <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-6 text-center">

                    <a href="#" class="btn btn-secondary"> Show more <i class="fas fa-arrow-down"></i></a>

                </div>
            </div>
        </div> -->
        <!-- END SHOW MORE -->
        <!-- CENTER -->
        <script>
            var perPage = 10;
            ShowMore(perPage);

            $(document).on("click", "#show_more", function() {

                perPage += 5;
                ShowMore(perPage);

            });

            function ShowMore(perPage) {

                $.get("getDate.php", {
                        direction: "user_posts",
                        person: <?php echo $user_id; ?>,
                        perPage: perPage
                    })
                    .done(function(data) {
                        $("#user_posts").html(data);

                    });

            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

</body>

</html>