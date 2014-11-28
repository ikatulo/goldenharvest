<?php
/*
Template Name: Login
*/
?>
<?php get_header();?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="loginWrapper">
                <?php if(have_posts()):?>
                    <?php while(have_posts()) : the_post();?>
                        <?php the_content();?>
                    <?php endwhile;?>
                <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer();?>
