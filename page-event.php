<?php
/* Template Name: Events*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<h1 class="page-title"><span class="glyphicon glyphicon-calendar"></span> <?php echo $post->post_title;?> <a href="<?php echo bloginfo('url');?>/events/add/" class="uibutton large special" title="Add Event">Add Event</a></h1>

<div class="main-content">
	<div id="calendar"></div>
</div>

<div class="clearfix"></div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>