<?php
/**
 * @package Bravis-Themes
 */
?>
		</div><!-- #main -->

		<?php autoev()->footer->getFooter(); ?>
		<?php do_action( 'pxl_anchor_target') ?>
		</div><!-- #wapper -->
		<?php if (class_exists('Bravis_User')) { ?>
			<?php autoev_user_form(); ?>
		<?php } ?>
	<?php wp_footer(); ?>
	</body>
</html>
