<?php
/*
 * @package awps
*/

if (!is_active_sidebar('awps-sidebar')) {
    return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar('awps-sidebar'); ?>
</aside><!-- #secondary -->
