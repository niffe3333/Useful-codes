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
ob_end_flush();

$direction = $_GET['direction'];
if (isset($_GET['perPage']) && $_GET['perPage'] >= 1) {
    $perPage = intval($_GET['perPage']);
} else {
    $perPage = 10;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// group list
if ($direction == "group_list" && isset($_GET['search'])) :
    $search = $_GET['search'];
    $all_groups = new classes\group();
    $all_groups_list = $all_groups->show_all_groups("all", $search, $perPage);

?>
    <?php foreach ($all_groups_list as $one_group) : ?>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-1">
                <div class="ratio img-responsive img-circle" style="background-image: url('img/test-person.jpg');">

                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $one_group['title']; ?></h5>
                        <p class="card-text"><?php echo $one_group['description']; ?></p>
                        <a href="group-post.php?group=<?php echo $one_group['id']; ?>" class="btn btn-secondary">View</a>

                    </div>
                    <div class="card-footer text-muted">
                        <?php echo $all_groups->Show_number_of_members($one_group['id']); ?> <i class="fas fa-users"></i> Created <?php echo $one_group['creation_date']; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
    <!-- SHOW MORE -->
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-6 text-center">

                <button id="show_more" class="btn btn-secondary"> Show more <i class="fas fa-arrow-down"></i></button>

            </div>
        </div>
    </div>
    <!-- END SHOW MORE -->
    <!-- END GROUP LIST -->
<?php elseif ($direction == "group_posts" && isset($_GET['group'])) :
    //dodać sprawdzenie grupy czy istnieje jeżeli nie nie wykonuj dalej kodu
    $group_id = $_GET['group'];
    $post = new classes\post();

    $this_group_posts = $post->show_all_posts('group', $group_id, $perPage);

?>
    <?php foreach ($this_group_posts as $one_post) : ?>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-1">
                <div class="ratio img-responsive img-circle" style="background-image: url('img/test-person.jpg');">

                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $one_post['title']; ?></h5>
                        <p class="card-text"><?php echo $one_post['content']; ?><?php if ($one_post['img'] != '') : if (file_exists("posts_img/" . $one_post['img'])) : ?><img src="<?php echo 'posts_img/' . $one_post['img']; ?>" class="img-fluid" alt="..."><?php endif;
                                                                                                                                                                                                                                                        endif; ?></p>
                        <a href="#" class="btn btn-secondary">View</a>

                    </div>
                    <div class="card-footer text-muted">
                        0 <i class="fas fa-heart"></i> 0 <i class="fas fa-comments"></i> 2 days ago
                    </div>
                </div>

            </div>
        </div>


    <?php endforeach; ?>
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-6 text-center">

                <button id="show_more" class="btn btn-secondary"> Show more <i class="fas fa-arrow-down"></i></button>

            </div>
        </div>
    </div>

    <!-- MY FRIENDS LIST -->
<?php elseif ($direction == "my_friends" && isset($_GET['search'])) :
    $this_user = new classes\user();
    $this_user->user_id($_SESSION['user_id']);
    $friends_list = $this_user->friends($_GET['search']);

?>
    <?php foreach ($friends_list as $one_friend) : ?>
        <li class="list-group-item">
            <a href="profile.php?person=<?php echo $one_friend['id'];?>">
                <div class="row ">

                    <div class="col-3">
                        <div class="ratio img-responsive img-circle" style="background-image: url('img/test-person.jpg');"></div>
                    </div>
                    <div class="col-9 justify-content-center align-self-center"><?php echo $one_friend['first_name'] . ' ' . $one_friend['last_name']; ?></div>

                </div>
            </a>
        </li>
    <?php endforeach; ?>
    <!-- END MY FRIENDS LIST -->

<?php endif; ?>