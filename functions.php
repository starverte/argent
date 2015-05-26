<?php
/**
 * Functions
 *
 * All function definitions for the site
 *
 * @author Matt Beall
 */

/**
 * Checks to see if a particular user is logged in
 *
 * @since 0.0.3
 *
 * @uses get_user_data() Gets ID of user object
 * @uses is_logged_in() Checks to see if anyone is logged in
 *
 * @param object $_user The user to check against
 * @return bool
 * @var int $u_id The ID of the user object
 * @var int $u_id    The ID of the user logged in
 */
function is_user_logged_in( $_user ) {
  if (is_logged_in()) {
    $u_id = !empty($_user) ? get_user_data( $_user , 'u_id' ) : 0;
    $u_id    = (int) $_SESSION['u_id'];

    if ($u_id == $u_id)
      return true;
    else
      return false;
  }
  else {
    return false;
  }
}

/**
 * Checks to see if anyone is logged in
 *
 * @since 0.0.3
 *
 * @uses get_user() Gets user object to make sure user actually exists
 *
 * @return bool
 * @var    int    $u_id  The ID of the user logged in
 * @var    object $_user The user object with the ID of the user logged in
 *
 * @todo Do additional checks besides just if the id exists
 */
function is_logged_in() {
  if (!empty($_SESSION['u_id'])) {
    global $edb;
    $u_id = (int) $_SESSION['u_id'];
    $_user = get_user($u_id);

    if (!empty($_user))
      return true;
    else
      return false;
  }
  else {
    return false;
  }
}

/**
 * Check to see if the user is logged in as an administrator
 *
 * @since 0.0.3
 *
 * @uses is_logged_in()  Check to see if anyone is even logged in
 * @uses is_user_admin() Check to see if the user logged in is an admin
 *
 * @return bool
 * @var    int    $u_id  The ID of the user logged in
 * @var    object $_user The user object with the ID of the user logged in
 */
function is_admin() {
  if (is_logged_in()) {
    $u_id = (int) $_SESSION['u_id'];
    $_user = get_user($u_id);

    if (is_user_admin($_user))
      return true;
    else
      return false;
  }
  else {
    return false;
  }
}

/**
 * Sanitize input to hexadecimal value
 *
 * First, checks to make sure that string only contains 0-9 and lowercase a-f.
 * Then, checks to make sure length is either 6 or 3 (shorthand).
 * If longer than 6, truncates to 6. If less than 6 and not 3, returns nothing.
 *
 * @since 0.0.4
 *
 * @param  string      $string The string to sanitize
 * @return string|void
 * @var    string      $new The sanitized string
 * @var    int         $len The length of $str
 */
function _hexadec( $string ) {
  $new = preg_replace( '[^0-9a-f]', '', strtolower($string) );
  $len = strlen($str);

  if ($len == 6 | $len == 3)
    return $new;
  elseif ($len > 6)
    return substr($new,0,6);
  else
    return;
}

/**
 * Sanitize text input and trim to size
 *
 * First, make sure only numbers and letters are used.
 * Next, if length is specificied, trim to length.
 *
 * @since 0.0.4
 *
 * @param  string $text The string to sanitize
 * @param  int    $length The length of the string
 * @return string
 * @var    string $new The sanitized string
 */
function _text( $text, $length = 0 ) {
  $new = preg_replace( '[^0-9a-fA-F]', '', $text);

  $length = (int) $length;

  if ( $length != 0 )
    return substr($new, 0, $length);
  else
    return $new;
}

/**
 * Sanitize email input and trim to size
 *
 * First, make sure string is an email address.
 * Next, if length is specificied, trim to length.
 *
 * @since 0.0.4
 *
 * @param  string $email The string to sanitize
 * @param  int    $length The length of the string
 * @return string
 * @var    string $new The sanitized string
 */
function _email( $email, $length = 0 ) {
  $new = filter_var($email, FILTER_VALIDATE_EMAIL);

  $length = (int) $length;

  if ( $length != 0 )
    return substr($new, 0, $length);
  else
    return $new;
}

/** @since 0.2.0 */
function get_tags() {
  global $edb;
  $results = $edb->select( 'tags', '*' );
  return $results;
}
