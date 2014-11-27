<?php
/* Template Name: Others */
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<style>img {max-width:100%; padding-bottom:10px;}</style>

<div class="main-content" style="height:325px;">

<div class="col-md-12" style="float: left; margin-bottom: 20px;">

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/vehicles"><img src="<?php echo bloginfo('template_directory');?>/images/vehicles.png"></a></div>

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/trackers"><img src="<?php echo bloginfo('template_directory');?>/images/tracker.png"></a></div>

<div class="col-md-2"></div>

<div class="col-md-2"></div>

<div class="col-md-2"></div>

<div class="col-md-2"></div>

</div>

</div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>