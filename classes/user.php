<?php

namespace classes;

use Exception;

class user extends db
{

    private $username;
    private $firstname;
    private $lastname;
    private $email;
    private $is_logged = false;
    private $msg = array();
    private $error = array();
    private $user_id;
    private $search;
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        parent::__construct();
        $this->search = '';
        $this->update_messages();

        // Checks if the user is logged in
        if (!empty($_SESSION['is_logged']) && $_SESSION['is_logged']) {

            $this->is_logged = true;
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
                    && $this->_verify_email() == true
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
                    if ($this->_verify_email() != true) {
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
    private function _verify_email()
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




    /*
Show user information

*/
    private function _user_exist()
    {

        $user_count = parent::query('SELECT * FROM users WHERE id = ? ', "$this->user_id")->row_count();
        if ($user_count > 0) {
            $user_exist = true;
        } else {
            $user_exist = false;
        }

        return $user_exist;
    }


    private function fetch_user_info()
    {
        if ($this->user_id != Null && $this->_user_exist()) {


            $user_info = parent::query('SELECT * FROM users WHERE id = ? ', "$this->user_id")->fetchArray();

            return $user_info;
        } else {
            header('Location: index.php');
            exit();
        }
    }

    public function user_info()
    {
        $user_info = $this->fetch_user_info();
        return $user_info;
    }

    public function user_id($user_id = null)
    {
        if ($user_id != null) {
            $this->user_id =  parent::real_escape_string($user_id);
            $user_exists = $this->_user_exist();

            if (!is_numeric($this->user_id)) {
                header('Location: index.php');
                exit();
            }
            if ($user_exists == false) {

                header('Location: index.php');
                exit();
            }
        }
    }

    private function check_account_owner()
    {

        $owner_id = parent::query('SELECT id FROM users WHERE id = ? ', "$this->user_id")->fetchArray();
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        if ($owner_id['id'] == $logged_account_id) {
            $owner_profile = true;
        } else {
            $owner_profile = false;
        }

        return $owner_profile;
    }

    public function account_owner()
    {

        $owner_profile = $this->check_account_owner();
        return $owner_profile;
    }


    private function fetch_friends_list()
    {
        $friends_list = parent::query('SELECT first_name, last_name, users.id FROM users INNER JOIN friend_ship ON users.id = friend_ship.friend_id WHERE user_id = ? AND users.first_name LIKE ? AND users.last_name LIKE ? AND user_accepted = ? AND friend_accepted = ?', "$this->user_id", "%$this->search%", "%$this->search%", 1, 1)->fetchAll();
        return $friends_list;
    }
    public function friends($search = '')
    {
        $this->search = parent::real_escape_string($search);

        $friends_list = $this->fetch_friends_list();
        return $friends_list;
    }


    private function fetch_user_friendship()
    {
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        $user_friend = parent::query('SELECT * FROM friend_ship WHERE friend_id = ? AND user_id = ? AND user_accepted = ? AND friend_accepted = ?', "$this->user_id", "$logged_account_id", 1, 1)->row_count();
        if ($user_friend > 0) {
            $user_friend_exists = true;
        } else {

            $user_friend_exists = false;
        }

        return $user_friend_exists;
    }

    public function user_friendship()
    {
        $user_friend_exists = $this->fetch_user_friendship();
        return $user_friend_exists;
    }


    private function _send_intivation()
    {
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        $user_friend = parent::query('SELECT * FROM friend_ship WHERE friend_id = ? AND user_id = ?', "$this->user_id", "$logged_account_id")->row_count();
        if ($user_friend > 0) {
            $user_friend_exists = true;
        } else {

            $user_friend_exists = false;
        }
        if ($user_friend_exists == false) {

            db::query(
                'INSERT INTO friend_ship (`id`, `user_id`, `friend_id`, `user_accepted`, `friend_accepted`, `readed`,`accepted`) VALUES (?,?,?,?,?,?,?) , (?,?,?,?,?,?,?)',
                NULL,
                "$logged_account_id",
                "$this->user_id",
                1,
                0,
                1,
                0,
                NULL,
                "$this->user_id",
                "$logged_account_id",
                0,
                1,
                0,
                0
            );
        }
    }

    public function send_invitation()
    {

        $this->_send_intivation();
    }

    private function _invitation_sent()
    {
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        $user_friend_exists = $this->fetch_user_friendship();
        if ($user_friend_exists == false) {
            $invitation_sent = parent::query('SELECT * FROM friend_ship WHERE friend_id = ? AND user_id = ? AND friend_accepted = ? AND user_accepted = ?', "$this->user_id", "$logged_account_id", 0, 1)->row_count();
            if ($invitation_sent > 0) {
                $invitation_sent_exists = true;
            } else {

                $invitation_sent_exists = false;
            }
            return $invitation_sent_exists;
        }
    }

    public function invitation_sent()
    {

        $invitation_sent_exists = $this->_invitation_sent();
        return $invitation_sent_exists;
    }


    private function _invitation_get()
    {
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        $user_friend_exists = $this->fetch_user_friendship();
        if ($user_friend_exists == false) {
            $invitation_get = parent::query('SELECT * FROM friend_ship WHERE friend_id = ? AND user_id = ? AND friend_accepted = ? AND user_accepted = ?', "$this->user_id", "$logged_account_id", 1, 0)->row_count();
            if ($invitation_get > 0) {
                $invitation_get_exists = true;
            } else {

                $invitation_get_exists = false;
            }
            return $invitation_get_exists;
        }
    }

    public function invitation_get()
    {

        $invitation_get_exists = $this->_invitation_get();
        return $invitation_get_exists;
    }


    private function _invitation_accept()
    {
        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);
        db::query(
            'UPDATE friend_ship SET `friend_accepted` = ? , `user_accepted` = ? WHERE user_id = ? AND friend_id = ? OR user_id = ? AND friend_id = ?',
             1,
             1,
            "$this->user_id",
            "$logged_account_id",
            "$logged_account_id",
            "$this->user_id"
        );

    }

    public function invitation_accept()
    {

        $invitation_get = $this->_invitation_get();
        if ($invitation_get == true) {
            $this->_invitation_accept();
        }
    }

    private function _remove_friendship()
    {

        $logged_account_id =  parent::real_escape_string($_SESSION['user_id']);

        db::query(
            'DELETE FROM friend_ship WHERE friend_id = ? AND user_id = ? OR friend_id = ? AND user_id = ? ',
            "$this->user_id",
            "$logged_account_id",
            "$logged_account_id",
            "$this->user_id"
        );
    }

    public function remove_friendship()
    {

        $this->_remove_friendship();
    }
 
}
