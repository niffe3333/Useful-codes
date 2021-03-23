<?php

namespace classes;

class group extends db
{
    private $available_whom;
    private $whom;
    private $group_id;
    private $search;
    private $title;
    private $description;
    private $tags;
    private $owner_id;
    private $perPage;
    private $user_id;
    public function __construct()
    {
        parent::__construct();

        $this->available_whom = ['my', 'other', 'all'];
        $this->search = '';
        $this->user_id = parent::real_escape_string($_SESSION['user_id']);

    }
    /*
Fetch All groups

*/

    private function fetch_all_groups()
    {
        if ($this->whom != Null) {
            if (in_Array($this->whom, $this->available_whom)) {
                $search = parent::real_escape_string($this->search);
                $perPage = parent::real_escape_string($this->perPage);

                if ($this->whom == "my") {

                    $groups = parent::query('SELECT * FROM u_group WHERE owner_id = ? ', "$this->user_id")->fetchAll();
                }
                if ($this->whom == "other") {


                    $groups = parent::query(' Select u_group.id , u_group.title FROM u_group INNER JOIN u_group_members ON u_group.id = u_group_members.group_id WHERE u_group_members.user_id = ? AND owner_id != ?', "$this->user_id", "$this->user_id")->fetchAll();
                }

                if ($this->whom == "all") {
                    $groups = parent::query('SELECT * FROM u_group WHERE u_group.tags LIKE ? OR u_group.title LIKE ? LIMIT ?', "%$search%", "%$search%", $perPage)->fetchAll();
                }
                return $groups;
            }
        }
    }
    /*
Show All groups

*/
    public function show_all_groups($whom = false, $search = '', $perPage = 10)
    {
        if (intval($perPage) >= 1) {

            $this->perPage = intval($perPage);
        } else {
            $this->perPage = 10;
        }

        $this->search = $search;
        $this->whom = $whom;
        $groups = $this->fetch_all_groups();
        return $groups;
    }

    /*
Add group

*/

    /*
Show group information

*/
    private function fetch_group_info()
    {
        if ($this->group_id != Null && $this->group_exist()) {


            $group_info = parent::query('SELECT * FROM u_group WHERE id = ? ', "$this->group_id")->fetchArray();

            return $group_info;
        } else {
            header('Location: index.php');
            exit();
        }
    }
    /*
Show group info

*/
    public function show_group_info()
    {

        $group_info = $this->fetch_group_info();
        return $group_info;
    }

    /*
Number of posts

*/
    public function Show_number_of_members()
    {


        $member_count = parent::query('SELECT * FROM u_group_members WHERE group_id = ? ', "$this->group_id")->row_count();
        return $member_count;

    }

    public function group_exist()
    {

        $member_count = parent::query('SELECT * FROM u_group WHERE id = ? ', "$this->group_id")->row_count();
        if ($member_count > 0) {
            $group_exist = true;
        } else {
            $group_exist = false;
        }

        return $group_exist;
    }



    private function insert_into_group()
    {

        if (strlen($this->title) > 0 && strlen($this->title) < 100 && $this->owner_id != Null) {


            db::query(
                'INSERT INTO u_group (id,title,description,owner_id,tags) VALUES (?,?,?,?,?)',
                NULL,
                "$this->title",
                "$this->description",
                "$this->user_id",
                "$this->tags"
            );
            header('Location: group-post.php?group=' . $this->connection->insert_id);
        }
    }

    public function add_group($title = false, $description = "", $tags = "")
    {

        $this->title = parent::real_escape_string($title);
        $this->description = parent::real_escape_string($description);
        $this->tags = parent::real_escape_string($tags);


        $this->insert_into_group();
    }



    private function update_group()
    {

        if (strlen($this->title) > 0 && strlen($this->title) < 100 && $this->user_id != Null) {

            // UPDATE `u_group` SET `title` = 'Programming PHPs', `description` = 'Programming PHPs', `tags` = 'Programming PHPs' WHERE `u_group`.`id` = 1;
            db::query(
                'UPDATE u_group SET `title` = ? , `description` = ? ,`tags` = ? WHERE id = ? AND owner_id = ?',

                "$this->title",
                "$this->description",
                "$this->tags",
                "$this->group_id",
                "$this->user_id"
            );

            header('Location: group-post.php?group=' . $this->group_id);
        }
    }

    public function change_group($title = false, $description = "", $tags = "")
    {


        $this->title = parent::real_escape_string($title);
        $this->description = parent::real_escape_string($description);
        $this->tags = parent::real_escape_string($tags);


        $this->update_group();
    }

    private function check_whether_group_member()
    {

        $member_count = parent::query('SELECT * FROM u_group_members WHERE group_id = ? AND user_id = ?', "$this->group_id", "$this->user_id")->row_count();
        if ($member_count > 0) {
            $whether_group_member = true;
        } else {
            $whether_group_member = false;
        }


        return $whether_group_member;
    }
    public function whether_group_member()
    {
        $whether_group_member = $this->check_whether_group_member();
        return $whether_group_member;
    }





    /*
Delete post from database

*/

    private function permament_delete_post($id)
    {

        db::query(
            'DELETE FROM `groups` WHERE `groups`.`id` = ?',
            $id
        );
    }

    public function delete_post($id)
    {

        $this->permament_delete_post($id);
    }


    /*
Number of groups

*/

    public function Show_number_groups()
    {

        $groups = parent::query('SELECT * FROM u_groups')->row_count();
        return $groups;
    }


    private function add_user_to_group()
    {

        db::query(
            'INSERT INTO u_group_members (id,group_id,user_id) VALUES (?,?,?)',
            NULL,
            "$this->group_id",
            "$this->user_id"
        );
    }
    public function join_the_group()
    {

        if ($this->check_whether_group_member() == false && $this->group_exist() == true) {

            $this->add_user_to_group();
        }
    }

    private function check_group_owner()
    {

        $owner_id = parent::query('SELECT owner_id FROM u_group WHERE id = ? ', "$this->group_id")->fetchArray();

        if ($owner_id['owner_id'] == $this->user_id) {
            $owner_group = true;
        } else {
            $owner_group = false;
        }

        return $owner_group;
    }

    public function group_owner()
    {

        return $this->check_group_owner();
    }




    public function group_id($group_id = null)
    {
        if ($group_id != null) {
            $this->group_id =  parent::real_escape_string($group_id);
            $group_exist = $this->group_exist();

            if (!is_numeric($this->group_id)) {
                header('Location: group-list.php');
                exit();
            }
            if ($group_exist == false) {

                header('Location: group-list.php');
                exit();
            }
          
        }
    }
}
