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

<div class="tsv-template-path <?php echo $class; ?>">
	<?php echo $path; ?>
</div>