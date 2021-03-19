<?php

if (!isset($join_file) && !$join_file) {
    header('Location: login.php');
    exit();
}
$group = new classes\group();
$all_my_groups = $group -> show_all_groups("my");
$all_my_other_groups = $group -> show_all_groups("other");
?>

<ul class="list-group navbar-left">
  
        <li class="list-group-item"><a href="add-group.php">ADD NEW <i class="fas fa-plus"></i></a></li>  
        <li class="list-group-item disabled title_navbar">Your group</li>
            
        <li class="list-group-item"><input class="form-control form-control-sm me-2 w-100 form-rounded" id="search_group" type="search" placeholder="Search group" aria-label="Search"></li>
        <?php foreach($all_my_groups as $one_my_group):?>
            <li class="list-group-item"> <a href="group-post.php?group=<?php echo $one_my_group['id'];?>"><?php echo $one_my_group['title'];?></a></li>
        <?php endforeach;?>
        <li class="list-group-item disabled title_navbar"> Group</li>
        <?php foreach($all_my_other_groups as $one_my_other_group):?>
            <li class="list-group-item"><?php echo $one_my_other_group['title'];?></li>
        <?php endforeach;?>
    </ul>