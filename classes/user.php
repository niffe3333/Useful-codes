<?php

namespace classes;

use Exception;

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

    /**
     * Simple register
     *
     * @param string $username
     * @param string $password
     * @param string $confrim
     * @param string $email
     */
    public function register($username = false, $password = false, $confrim = false, $email = false)
    {

        if (!empty($username) && !empty($password) && !empty($confrim) && !empty($email)) {

            if ($password == $confrim) {


                $this->username = db::real_escape_string($username);
                $password = sha1(db::real_escape_string($password));
                $this->email = db::real_escape_string($email);

                if (
                    $this->check_unique_nickname() == 0
                    && $this->check_unique_email() == 0
                    && $this->verify_email() == true
                    && $this->verify_nick_alphanumeric() == true
                    && $this->verify_username_lenght() == true
                    && $this->verify_password_lenght() == true
                ) {

                    try {
                        db::query(
                            'INSERT INTO users (`id`, `username`, `password`, `email`, `email_confirm`) VALUES (?,?,?,?,?)',
                            NULL,
                            "$this->username",
                            "$password",
                            "$this->email",
                            1
                        );
                    } catch (Exception $e) {
                        // echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }

                    $this->msg[] = 'User created!';
                    $_SESSION['msg'] = $this->msg;
                } else {
                    unset($_SESSION['msg']);
                    if ($this->check_unique_nickname() != 0) {
                        $this->error[] = 'Username already taken!';
                    }
                    if ($this->verify_nick_alphanumeric() != true) {
                        $this->error[] = 'Username must contain only characters, only alphanumeric characters!';
                    }
                    if ($this->verify_username_lenght() != true) {
                        $this->error[] = 'Username must be between 3 and 32 characters!';
                    }
                    if ($this->check_unique_email() != 0) {
                        $this->error[] = 'E-mail address is already taken!';
                    }
                    if ($this->verify_email() != true) {
                        $this->error[] = 'E-mail address is incorrect!';
                    }
                    if ($this->verify_password_lenght() != true) {
                        $this->error[] = 'The password must be at least 8 characters long!';
                    }
                }
            } else $this->error[] = 'The passwords do not match!';
        } elseif (empty($username)) {

            $this->error[] = 'The username field is empty!';
        } elseif (empty($password)) {

            $this->error[] = 'The password field is empty!';
        } elseif (empty($confrim)) {

            $this->error[] = 'The password repeat field is empty!';
        } elseif (empty($email)) {

            $this->error[] = 'The e-mail field is empty!';
        }
    }



    /*Data validation during account registration
    /
    /
    */
    private function verify_password_lenght()
    {
        $password_lenght = strlen($this->username);
        $valid_password_lenght = false;
        if ($password_lenght < 8 || $password_lenght > 100) {
            $valid_password_lenght = false;
        } else {
            $valid_password_lenght = true;
        }
        return $valid_password_lenght;
    }
    // Checking the username length
    private function verify_username_lenght()
    {
        $username_lenght = strlen($this->username);
        $valid_username_lenght = false;
        if ($username_lenght < 3 || $username_lenght > 32) {
            $valid_username_lenght = false;
        } else {
            $valid_username_lenght = true;
        }
        return $valid_username_lenght;
    }
    // Checking alphanumeric characters
    private function verify_nick_alphanumeric()
    {
        $alphanumeric_nickname = false;
        if (preg_match('![^a-zA-Z0-9]+!i', $this->username)) {
            $alphanumeric_nickname = false;
        } else {
            $alphanumeric_nickname = true;
        }
        return $alphanumeric_nickname;
    }
    // Validation of the e-mail address
    private function verify_email()
    {
        $valid_email = false;

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $valid_email = true;
        } else {
            $valid_email = false;
        }
        return $valid_email;
    }


    // Checking the uniqueness of the usernames in the database
    private function check_unique_nickname()
    {
        $accounts = db::query(
            'SELECT * FROM `users` WHERE username = ?',
            "$this->username"

        )->fetchArray();

        $account_count = count($accounts);

        return $account_count;
    }
    // Checking the uniqueness of the email address in the database
    private function check_unique_email()
    {
        $accounts = db::query(
            'SELECT * FROM `users` WHERE email = ?',
            "$this->email"

        )->fetchArray();

        $account_count = count($accounts);

        return $account_count;
    }
}
