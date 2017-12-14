			<?php crb_render_fragment( 'callout' ); ?>
		</div><!-- /.main -->

		<footer class="footer">
			<?php if ( is_active_sidebar( 'widgetized-footer' ) ): ?>
				<div class="footer__inner">
					<div class="shell">
						<ul class="widgets-footer">
							<?php dynamic_sidebar( 'widgetized-footer' ); ?>
						</ul><!-- /.widgets-footer -->
					</div><!-- /.shell -->
				</div><!-- /.footer__inner -->
			<?php endif; ?>

			<div class="footer__bar">
				<div class="shell">
					<div class="footer__copyright">
						<p>
							<?php
							printf(
								__( 'Copyright &copy; %1$s %2$s. All rights reserved.', 'crb' ),
								date( 'Y' ),
								get_bloginfo( 'name' )
							);
							?>
						</p>

						<p>Site by <a href="http://travelingstorytellers.com/" target="_blank">Traveling Storytellers</a></p>
					</div><!-- /.footer__copyright -->

					<?php crb_render_fragment( 'socials' ); ?>
				</div><!-- /.shell -->
			</div><!-- /.footer__bar -->
		</footer><!-- /.footer -->
	</div><!-- /.wrapper -->
	<?php wp_footer(); ?>
</body>
</html>
