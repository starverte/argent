<?php
/**
 * Home Page
 *
 * This is the "home" page, often the first page
 * that a user encounters when visiting the website.
 *
 * @author Matt Beall
 * @since 0.0.1
 */

global $the_title;
$the_title = 'Index';

include_once('header.php'); ?>

    <div id="primary" class="content-area container">
      <div id="content" class="site-content col-lg-12 col-md-12" role="main">
        <div class="row">
          <article class="page type-page status-draft hentry col-lg-12 col-md-12 col-sm-12">
            <header class="entry-header">
              <h1 class="entry-title"><?php echo $the_title; ?></h1>
            </header><!-- .entry-header -->

            <div class="entry-content">
              <pre><?php $invoices = get_invoices(); print_r($invoices); ?></pre>
            </div><!-- .entry-content -->
          </article>
        </div><!-- .row -->
      </div><!-- #content -->
    </div><!-- #primary -->

<?php include_once('footer.php'); ?>
