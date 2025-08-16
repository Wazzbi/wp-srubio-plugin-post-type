<?php get_header(); ?>
<?php
$layout = _themename_meta(get_the_ID(), '__themename_post_layout');
$sidebar = $layout === "with-sidebar" && is_active_sidebar('primary-sidebar');
if (!$sidebar) {
    $layout = "default"; // Fallback to default layout if sidebar is not active.
}
?>
<div class="o-container u-margin-bottom-40 o-single-post-<?php echo $layout; ?>" style="background-color:red">
    <div class="o-row">
        <div class="o-row__column o-row__column--span-12 o-row__column--span-<?php echo $sidebar ? '8' : '12'; ?>@medium">
            <main role="main">
                <?php get_template_part("loop", "single") ?>
            </main>
        </div>

        <?php if ($sidebar) { ?>
            <div class="o-row__column o-row__column--span-12 o-row__column--span-4@medium">
                <?php get_sidebar() ?>
            </div>
        <?php } ?>

    </div>
</div>
<?php get_footer(); ?>