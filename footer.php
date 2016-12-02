<?php
/*
 * @package awps
*/
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php if (defined('ENV') && ENV === 'dev'): ?>

   <script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.17.5'><\/script>".replace("HOST", location.hostname));
//]]></script>

<?php endif ?>

<?php wp_footer(); ?>

</body>
</html>
