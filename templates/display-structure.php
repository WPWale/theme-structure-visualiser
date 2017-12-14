<?php
/**
 * Display Structure
 * 
 * Output the path wrapped in a div.
 * 
 * @author Shantanu Desai <shantanu2846@gmail.com>
 * @since 0.0.1
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) exit(); ?>

<div class="tsv-template-path <?php echo "tsv-$class"; ?>">
	<?php // $url = add_query_arg(array('file' => $path),admin_url( 'theme-editor.php' )); ?>
	<a href="<?php echo $url ?>" title="Click to edit in WordPress editor">
		<?php echo $path; ?>
	</a>
</div>