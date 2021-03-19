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
    <title>Hello, world!</title>
</head>

<body>
  
    <!-- NAVBAR -->
    <?php require_once 'header.php';?>
    <!-- END NAVBAR -->
    <!-- RIGHT BAR -->

    
    <?php require_once 'right_bar.php';?>
    <!-- END RIGHT BAR -->

    <!-- LEFT BAR -->
    <?php require_once 'left_bar.php';?>
    

    <!-- END LEFT BAR -->
    <!-- NEW POST -->
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-6 text-center">

                <a href="#" class="btn btn-success"> Add new post <i class="fas fa-plus"></i></a>

            </div>
        </div>
    </div>
    <!-- END NEW POST -->
    <!-- CENTER -->

    <div class="container">

        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-1">
                <div class="ratio img-responsive img-circle" style="background-image: url('img/test-person.jpg');">

                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">I bought new car!</h5>
                        <p class="card-text">THIS IS MY NEW CAR!<img src="img/car.jpg" class="img-fluid" alt="..."></p>
                        <a href="#" class="btn btn-secondary">View</a>

                    </div>
                    <div class="card-footer text-muted">
                        0 <i class="fas fa-heart"></i> 0 <i class="fas fa-comments"></i> 2 days ago
                    </div>
                </div>

            </div>
        </div>
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-1">
                <div class="ratio img-responsive img-circle" style="background-image: url('img/test-person.jpg');">

                </div>

            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">My new profile photo</h5>
                        <p class="card-text"><img src="img/test-person.jpg" class="img-fluid" alt="..."></p>
                        <a href="#" class="btn btn-secondary">View</a>

                    </div>
                    <div class="card-footer text-muted">
                        0 <i class="fas fa-heart"></i> 0 <i class="fas fa-comments"></i> 2 days ago
                    </div>
                </div>

            </div>
        </div>


    </div>
    <!-- SHOW MORE -->
    <div class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-md-6 text-center">

                <a href="#" id="show_more" class="btn btn-secondary"> Show more <i class="fas fa-arrow-down"></i></a>

            </div>
        </div>
    </div>
    <!-- END SHOW MORE -->
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