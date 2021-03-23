<?php

if (!isset($join_file) && !$join_file) {
    header('Location: login.php');
    exit();
}
?>
<style>
    body {
        margin-top: 100px;
        font-family: 'Poppins', sans-serif;
        font-weight: 200;
    }

    .dropdown-toggle::after {
        content: none;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .btn {
        font-weight: 200 !important;
    }

    .fas {
        margin-left: 5px;
        margin-right: 5px;
    }

    .form-rounded {
        border-radius: 1rem;
    }

    .list-group-item {
        border: none;
        transition: 0.2s;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .fa-heart {
        transition: 0.2s;
    }

    .fa-heart:hover {
        color: red;
        cursor: pointer;
    }

    .fa-trash {
        transition: 0.2s;
    }

    .fa-trash:hover {
        color: red;
        cursor: pointer;
    }

    h1 {
        color: #6c757d;
    }

    a {
        text-decoration: none;
    }

    .search_icon {

        float: right;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: transparent;
        border-width: 0px;
        color: #6c757d;
        text-decoration: none;
    }

 
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">LifeLite</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <form action="group-list.php" method="GET" class="d-flex ms-auto w-100 justify-content-center">
                <input class="form-control form-control-sm me-2 w-50 form-rounded" id="search" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php if (isset($_GET['search'])) {
                                                                                                                                                                                echo $_GET['search'];
                                                                                                                                                                            }; ?>">

                <button type="submit" class="search_icon"><i class="fas fa-search"></i></button>

            </form>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 px-2">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="profile.php?person=<?php echo $_SESSION['user_id'];?>"><i class="fas fa-user"></i></a>
                </li>
                <!-- Message dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Show All</a></li>
                        <li><a class="dropdown-item" href="#">You have a new message!</a></li>
                        <li><a class="dropdown-item" href="#">You have a new message!</a></li>

                    </ul>
                </li>
                <!-- Info dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">You have a new like!</a></li>
                        <li><a class="dropdown-item" href="#">You have a new like!</a></li>

                    </ul>
                </li>
                <!-- Settings dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-arrow-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"> <i class="fas fa-cog"></i>Settings</a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>