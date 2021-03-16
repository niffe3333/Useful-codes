<?php

namespace classes;


class user extends db
{

    private $username;
    private $email;
    private $is_logged = false;
    private $msg = array();
    private $error = array();

    public function __construct()
    {
        session_start();
        parent::__construct();

        $this->update_messages();

        // Checks if the user is logged in
        if (!empty($_SESSION['username']) && $_SESSION['is_logged']) {

            $this->is_logged = true;
            $this->username = $_SESSION['username'];
        }
        return $this;
    }
    // Gets the username
    public function get_username()
    {
        return $this->username;
    }

    // Gets the e-mail

    public function get_email()
    {
        return $this->email;
    }

    // Check if the user is logged

    public function is_logged()
    {
        return $this->is_logged;
    }

    // Get info messages

    public function get_info()
    {
        return $this->msg;
    }

    // Get errors

    public function get_error()
    {
        return $this->error;
    }

    // Show info
    public function display_info()
    {
        foreach ($this->msg as $msg) {
            echo '<p class="msg">' . $msg . '</p>';
        }
    }

    // Show errors

    public function display_errors()
    {
        foreach ($this->error as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
    }

    // Updates errors and information
    private function update_messages()
    {
        if (isset($_SESSION['msg']) && $_SESSION['msg'] != '') {
            $this->msg = array_merge($this->msg, $_SESSION['msg']);
            $_SESSION['msg'] = '';
        }
        if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
            $this->error = array_merge($this->error, $_SESSION['error']);
            $_SESSION['error'] = '';
        }
    }

    // Logout

    public function logout()
    {

        session_unset();
        session_destroy();
        $this->is_logged = false;
    }





    /**
     * Login system
     *
     * @param string $username
     * @param string $password
     */
    public function login($username = false, $password = false)
    {
        if (!$this->is_logged) {
            if (!empty($username) && !empty($password)) {

                $this->username = db::real_escape_string($username);
                // convert password to sha1
                $this->password = sha1(db::real_escape_string($password));

                if ($row = $this->get_user()) {

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['email_confirm'] = $row['email_confirm'];
                    $_SESSION['is_logged'] = true;
                    $this->is_logged = true;
                    //redirect to index.php
                    header('Location: index.php');
                } else $this->error[] = 'Wrong user or password.';
            } elseif (empty($username)) {

                $this->error[] = 'Username field was empty.';
            } elseif (empty($password)) {

                $this->error[] = 'Password field was empty.';
            }
        } else {
            //There may be a redirect here if the user is logged in
            //header('Location: userpanel.php'); 
        }
    }

    private function get_user()
    {
        return db::query(
            'SELECT * FROM `users` WHERE username = ? AND password = ?',
            "$this->username",
            "$this->password"
        )->fetchArray();
    }
}
