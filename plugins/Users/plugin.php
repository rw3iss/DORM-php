<?php

/* Usermu user management library for PHP 
 * Requirements: PHP 5.5+ (for user of the bcryprt hasing algorithm)
 *
 * This library manages creation of users, user authentication, and general user management.
 * The user data properties/schema can be customized by modifying the models/user.php class,
 * and this library will automatically detect the user properties.
 * 
 * Help or general questions, contact: Ryan Weiss (rw3iss@gmail.com)
 *
 */

require_once('lib/user.php');

class Users extends DormPlugin {
	var $user_session_key = 'CURRENT_USER';

    function on_load() {
    }

    /*
     * Returns the User object associated with the current session.
     * If none exists, returns FALSE.
     */
    function current_user() {
    	$user = unserialize($this->dorm->Session->get($this->user_session_key));
    	return $user;
    }

	/*
	 * Registers a new user.
	 * Returns a User object if successful, or an Error object in case of an error.
	 * Parameters:
	 *  login = email or password
	 *  login_type = 'email' or 'username' (facebook...?)
	 *  options = any of the properties of the 'User' class in 'models/user.php'
	 */
	function create_user($username, $password, $email, $options) {
		//$bcrypt = new Bcrypt(15);
		//$password_hash = $bcrypt->hash($password);

		$hasher = new PasswordHash(8, false);
		$password_hash = $hasher->HashPassword($password);

		$sql = sprintf("INSERT INTO users (username, password, email, created, modified) VALUES
						('%s', '%s', '%s', '%s', '%s')", $username, $password_hash, $email, mysql_now(), mysql_now());

		$this->db->query($sql);

		$user = $this->get_user_by_username($username);

		return $user;
	}

	function login_user($username, $password, $remember) {
		//first find the user in the database:
		$user = $this->get_user_by_username($username);

		if($user) {
			//verify password:
			//$bcrypt = new Bcrypt(15);
			//$correct = $bcrypt->verify($password, $user->password);

			$hasher = new PasswordHash(8, false);
			//$hashed_password = $hasher->HashPassword($password);
			$correct = $hasher->CheckPassword($password, $user->password);

			if(!$correct) {
	            error_response(Error::INVALID_LOGIN, 'The username or password is incorrect.');
	            return;
			}
			else {
	    		unset($user->_columns);
				$this->native_session->set_userdata(USER_SESSION, serialize($user));

	            if($remember) {
	            	/*
	                $this->load->helper('cookie');  
	                $key = substr(md5(uniqid(rand().get_cookie($this->cookie_name))), 0, 16);

	                set_cookie(array(
	                        'name'      => $this->cookie_name,
	                        'value'     => serialize(array('user_id' => $user->id, 'key' => $key)),
	                        'expire'    => 60*60*24*31*2
	                ));
	                */
	            }

	    	}
		} 
		else {
			//user doesn't exist
            error_response(Error::INVALID_LOGIN, 'The username or password is incorrect.');
            return;
		}

    	return $user;
	}

	//checks if the user has a cookie set, and logs them in if so
	function autologin() {
		/*
		if (!$this->is_logged_in()) {
			$this->load->helper('cookie');

			if ($cookie = get_cookie($this->cookie_name, TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {
						// Login user
						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
								'value'		=> $cookie,
								'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
						));

						$this->ci->users->update_login_info(
								$user->id,
								$this->ci->config->item('login_record_ip', 'tank_auth'),
								$this->ci->config->item('login_record_time', 'tank_auth'));
						return TRUE;
					}
				}
			}
		}
		*/
	}

	function is_logged_in() {
    	$user = $this->current_user();

    	if($user != null) {
    		return true;
    	}

    	return false;
	}

	function set_user($user) {
		if($user != null) {
    		self::$SESSION[USER_SESSION] = $user;
		}

    	return $user;
	}

	function get_user_by_username($username) {
		$sql = "SELECT * from users WHERE username='" . $username ."'";
        
        $query = $this->db->query($sql);
        $row = $query->result('User');
        $row = $query->row(0, 'User');

        if($row) {
        	return $row;
        }

        return FALSE;
	}

	function get_user_by_id($id) {
		$sql = "SELECT * from users WHERE id='" . $username ."'";
        
        $query = $this->db->query($sql);
        $row = $query->result('User');
        $row = $query->row(0, 'User');

        if($row) {
        	return $row;
        }

        return FALSE;
	}

	function get_user_by_email($email) {
		$sql = "SELECT * from users WHERE email='" . $email ."'";
        
        $query = $this->db->query($sql);
        $row = $query->result('User');
        $row = $query->row(0, 'User');

        if($row) {
        	return $row;
        }

        return FALSE;
	}

	function update_user($user, $options) {
	}

	function delete_user($user) {
	}
	
}

?>