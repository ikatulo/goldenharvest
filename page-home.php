<?php
/* Template Name: Home */
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<style>img {max-width:100%; padding-bottom:10px;}</style>

<div class="main-content" style="height:325px;">

<div class="col-md-12" style="float: left; margin-bottom: 20px;">

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/hrmatters"><img src="<?php echo bloginfo('template_directory');?>/images/hrmatters.png"></a></div>

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/quotations"><img src="<?php echo bloginfo('template_directory');?>/images/quotations.png"></a></div>

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/events"><img src="<?php echo bloginfo('template_directory');?>/images/calendar.png"></a></div>

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/customers"><img src="<?php echo bloginfo('template_directory');?>/images/customers.png"></a></div>

<div class="col-md-2"><a href="<?php echo bloginfo('url');?>/others"><img src="<?php echo bloginfo('template_directory');?>/images/others.png"></a></div>

<div class="col-md-2"></div>

</div>

</div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>