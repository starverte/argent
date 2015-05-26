<?php
/**
 * Header
 *
 * Contains the opening <html> and <head> section
 * along with the header and navigation that is consistent
 * on all pages.
 *
 * @author Matt Beall
 * @since 0.2.0
 */
session_start();
//session_regenerate_id();

include_once('argent-config.php');
include_once('inc/class-argentdb.php');
include_once('inc/class-argent-invoice.php');
//include_once('inc/class-e-tag.php');
//include_once('inc/class-e-ticket.php');
//include_once('inc/class-e-moderator.php');
include_once('functions.php');

$ArgentDB = new ArgentDB;

global $the_title;
global $the_type;

if (!empty($the_type)) {
  switch ($the_type) {
    case 'user':
      global $user;
      if (!empty($_REQUEST['u'])) {
        $u = (int) $_REQUEST['u'];
        $user = get_user( $u );
      }
      else {
        $u = 0;
        $user = null;
      }
      break;

    default:
      break;
  }
}

if (is_logged_in()) {
}
elseif ($the_title = "Login") {
}
else {
  header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $the_title; ?> | echo</title>
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="stylesheet" id="bootstrap"  href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" type="text/css" media="all" />
  <link rel="stylesheet" id="open-sans"  href="//fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&#038;subset=latin%2Clatin-ext&#038;ver=4.0" type="text/css" media="all" />
  <link rel="stylesheet" id="flint"  href="//raw.githubusercontent.com/starverte/flint/master/style.css" type="text/css" media="all" />
  <link rel="stylesheet" id="flint"  href="//fortcollinscreative.com/wp-content/themes/canvas/style.css" type="text/css" media="all" />
  <link rel="stylesheet" id="stylesheet"  href="style.css" type="text/css" media="all" />
</head>
<body>
  <div id="page" class="hfeed site">

    <nav class="navbar navbar-canvas navbar-top" role="navigation">
      <h1 class="screen-reader-text">Menu</h1>
      <div class="screen-reader-text skip-link"><a href="#content" title="Skip to content">Skip to content</a></div>
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-c410">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><h1><tt>&lt;echo&gt;</tt></h1></a>
        </div><!-- .navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-c410">
          <ul id="menu-top" class="nav navbar-nav navbar-right">
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="list.php">Browse</a></li>
            <li><a href="create-ticket.php">Submit Ticket</a></li>
      <?php if (is_logged_in()) { ?>
      <li><a href="login.php?logout=1">Logout</a></li>
      <?php }
      else { ?>
            <li><a href="login.php">Login</a></li><?php
      } ?>
          </ul>
        </div><!-- .navbar-collapse -->
      </div><!-- .container -->
    </nav><!-- .navbar -->

    <div id="masthead" class="canvas site-header" role="banner">
      <div class="container">
        <div class="site-branding">
          <div class="clearfix"><p></p></div>
        </div><!-- .site-branding -->
      </div><!-- .container -->
    </div><!-- #masthead -->


    <div class="stripe">
      <div class="container">
        <p><?php echo $the_title; ?></p>
      </div><!-- .container -->
    </div><!-- .stripe -->
