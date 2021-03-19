<?php

namespace classes;

class group extends db
{
    private $available_whom;
    private $whom;
    private $group_id;

    private $title;
    private $description;
    private $tags;
    private $owner_id;
    public function __construct()
    {
        parent::__construct();

        $this->available_whom = ['my', 'other'];
        // $this->title = false;
        // $this->description = '';
        // $this->owner_id = false;
        // $this->tags = '';
    }
    /*
Fetch All groups

*/

    private function fetch_all_groups()
    {
        if ($this->whom != Null) {
            if (in_Array($this->whom, $this->available_whom)) {
                $user_id = parent::real_escape_string($_SESSION['user_id']);
                if ($this->whom == "my") {

                    $groups = parent::query('SELECT * FROM u_group WHERE owner_id = ? ', "$user_id")->fetchAll();
                }
                if ($this->whom == "other") {

                    $groups = parent::query('SELECT * FROM u_group WHERE owner_id != ? ', "$user_id")->fetchAll();
                }
                return $groups;
            }
        }
    }
    /*
Show All groups

*/
    public function show_all_groups($whom = false)
    {
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
    public function show_group_info($group_id = false)
    {
        $this->group_id = parent::real_escape_string($group_id);
        $group_info = $this->fetch_group_info();
        return $group_info;
    }

    /*
Number of posts

*/
    public function Show_number_of_members()
    {

        $member_count = parent::query('SELECT * FROM u_group_members WHERE id = ? ', "$this->group_id")->row_count();
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
                "$this->owner_id",
                "$this->tags"
            );
            header('Location: group-post.php?group='.$this->connection->insert_id);
        }
    }

    public function add_group($title = false, $description = "", $tags = "")
    {

        $this->title = parent::real_escape_string($title);
        $this->description = parent::real_escape_string($description);
        $this->tags = parent::real_escape_string($tags);
        $this->owner_id = parent::real_escape_string($_SESSION['user_id']);
       
         $this->insert_into_group();
     
    }



    private function update_group()
    {
       
        if (strlen($this->title) > 0 && strlen($this->title) < 100 && $this->owner_id != Null) {

            // UPDATE `u_group` SET `title` = 'Programming PHPs', `description` = 'Programming PHPs', `tags` = 'Programming PHPs' WHERE `u_group`.`id` = 1;
            db::query(
                'UPDATE u_group SET `title` = ? , `description` = ? ,`tags` = ? WHERE id = ? AND owner_id = ?',
              
                "$this->title",
                "$this->description",
                "$this->tags",
                "$this->group_id",
                "$this->owner_id"
            );
             header('Location: group-post.php?group='.$this->group_id);
        }
    }

    public function change_group($title = false, $description = "", $tags = "", $group_id = false)
    {

        $this->group_id = parent::real_escape_string($group_id);
        $this->title = parent::real_escape_string($title);
        $this->description = parent::real_escape_string($description);
        $this->tags = parent::real_escape_string($tags);
        $this->owner_id = parent::real_escape_string($_SESSION['user_id']);
       
         $this->update_group();
     
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
}
