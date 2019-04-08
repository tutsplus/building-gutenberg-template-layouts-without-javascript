<?php get_header();

$query = array('post_type' => 'landing', 'posts_per_page' => 1);

$loop = new WP_Query($query);

while( $loop->have_posts() ) : $loop->the_post();

global $post;

$lpcontent = apply_filters('landing_page_content', $post->post_content);



echo "<script>console.log(" . json_encode($lpcontent) . ");</script>";

?>

<div class="tours">
    <div class="inner inner_padding">
        <?php echo $lpcontent['tour_categories_heading']; ?>

        <div class="tour_categories">

            <?php foreach($lpcontent['tour_categories'] as $key => $tour_cat){ ?>

            <div class="<?php echo $key; ?>">
                <?php echo $tour_cat[0]; ?>
				<div class="tour_overlay">
                    <?php echo $tour_cat[1];
                    echo $tour_cat[2]; ?>
				</div>
            </div>

            <?php } ?>

        </div>

    </div>
</div>

<?php endwhile; get_footer(); ?>