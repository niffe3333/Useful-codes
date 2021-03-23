<ul class="list-group navbar-right d-none d-lg-block">
    <li class="list-group-item disabled"> Your friends <i class="fas fa-user-plus"></i></li>
    <li class="list-group-item"><input class="form-control form-control-sm me-2 w-100 form-rounded" id="search_friend" type="search" placeholder="Search friend" aria-label="Search"></li>

    <span id="friends_list">

    </span>
</ul>
<script>
    LoadFriends();

    var search_friends = "";

    LoadFriends(search_friends);

$(document).on("keyup", "#search_friend", function() {
    search_friends = $(this).val();
    LoadFriends(search_friends);
});

    function LoadFriends(search_friends) {

        $.get("getDate.php", {
                direction: "my_friends",
                search:search_friends

            })
            .done(function(data) {
                $("#friends_list").html(data);
                // alert(data);
            });

    }
</script>