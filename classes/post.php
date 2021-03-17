<?php

namespace classes;

class post extends db
{


    /*
Fetch All posts

*/

    private function fetch_all_posts()
    {

        $posts = parent::query('SELECT * FROM posts')->fetchAll();
        return $posts;
    }
    /*
Show All posts

*/
    public function show_all_posts()
    {

        $posts = $this->fetch_all_posts();
        return $posts;
    }

    /*
Add post

*/

    private function insert_into_post($title, $content, $slug)
    {

        db::query(
            'INSERT INTO posts (id,title,content,slug) VALUES (?,?,?,?)',
            NULL,
            "$title",
            "$content",
            "$slug"
        );
    
    }

    public function add_post($title, $content, $slug)
    {

        $this->insert_into_post($title, $content, $slug);
        return "Added to the database";
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
}
