<?php

namespace classes;

class post extends db
{
    private $where;
    private $where_id;
    private $owner_id;
    private $title;
    private $content;
    private $img;
    private $perPage;
    private $available_places_show;
    private $user_id;
    public function __construct()
    {
        parent::__construct();

        $this->available_places_show = ['group','person','my_and_friends'];
        $this->user_id = parent::real_escape_string($_SESSION['user_id']);
    }

    /*
Fetch All posts

*/

    private function fetch_all_posts()
    {
        // dorobić where_id itp
       if($this->where=='group'){
        $posts = parent::query('SELECT * FROM posts WHERE where_added_id = ? ORDER BY creation_date DESC LIMIT ?',"$this->where_id","$this->perPage")->fetchAll();
       }

        
        return $posts;
    }
    /*
Show All posts

*/
    public function show_all_posts($where = null, $where_id = null, $perPage = 10)
    {
       
        if($where!=null && $where_id !=null){
            if (intval($perPage) >= 1) {

                $this->perPage = intval($perPage);
            } else {
                $this->perPage = 10;
            }
            if(in_array($where,$this->available_places_show)){
                $this->where = parent::real_escape_string($where);
                $this->where_id = parent::real_escape_string($where_id);    ////////////////////////////zabezpieczyć to
                $posts = $this->fetch_all_posts();
                return $posts;
            }
        }
    }

    /*
Add post

*/

    private function insert_into_post()
    {

        $today = date("Y-m-d h:m:s");
        db::query(
            'INSERT INTO posts (id,title,content,img,creation_date,owner_id,where_added,where_added_id) VALUES (?,?,?,?,?,?,?,?)',
            NULL,
            "$this->title",
            "$this->content",
            "$this->img",
            "$today",
            "$this->owner_id",
            "$this->where",
            "$this->where_id"


        );
    }

    public function add_post($title = false, $content = '', $img = '', $where = false, $where_id = false)
    {

        if ($title != Null && $where != NUll && $where_id != Null) {
            $this->title = parent::real_escape_string($title);
            $this->content = parent::real_escape_string($content);
            $this->img = parent::real_escape_string($img);
            $this->where = parent::real_escape_string($where);
            $this->where_id = parent::real_escape_string($where_id);
            $this->owner_id = parent::real_escape_string($_SESSION['user_id']);

            $this->insert_into_post();
        }
    }


    /*
Delete post from database

*/

    private function permament_delete_post($id)
    {

        db::query(
            'DELETE FROM `posts` WHERE `posts`.`id` = ?',
            $id
        );
    }

    public function delete_post($id)
    {

        $this->permament_delete_post($id);
    }


    /*
Number of posts

*/

    public function Show_number_posts()
    {

        $posts = parent::query('SELECT * FROM posts')->row_count();
        return $posts;
    }


    
}
