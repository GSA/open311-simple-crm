 	  </div> <!-- /row --> 
 	  </div> <!-- /container -->  
 	</div> <!-- /main -->  

      <footer>
	 	  <div class="container">

		<?php 
			$org_url = config_item('organisation_url');
			if (! empty($org_url)) { ?>
				<div class="departmental-link">
					<a href="<?php echo $org_url; ?>" 
						class="<?php echo (preg_match('/https?:\/\/(\\w*\\.)*fixmy/', $org_url))? 'fmse-web-link-fms':'fmse-web-link' ?>"
						target="_blank"
					><?php 
					if (config_item('organisation_link_text')) {
						echo config_item('organisation_link_text');
					} else {
						echo $org_url;
					}
				?></a>
			</div>
		<?php } ?>
	    <div class="open311-status">
	      <a href="<?php echo site_url('admin/open311')?>" 
	        class="open311-status-<?php if(is_config_true(config_item('enable_open311_server'))) { echo('on');} else {echo('off');} ?>">
	        Open311 server is
	        <?php if(is_config_true(config_item('enable_open311_server'))) { echo('on');} else {echo('off');} ?>
	      </a>
	    </div>
	    <?php if (isset($auth) && $auth->logged_in()) { ?>
	    <nav role="navigation">
	    	<ul id="menu-footer-navigation" class="nav">
	    		<li><a href='<?php echo site_url('admin/about')?>' class="fmse-mysoc">about</a></li>
	    	</ul>
	    </nav>
	    <?php } ?>
		
		</div> <!-- /container -->    

      </footer>
    

<script>window.jQuery || document.write('<script src="<?php echo site_url('assets/fms-endpoint/js/vendor/jquery-1.10.1.min.js')?>"><\/script>')</script>

<script src="<?php echo site_url('assets/fms-endpoint/js/vendor/bootstrap.min.js')?>"></script>


<?php if (config_item('google_analytics_id')): ?>
						
	<script>
	    var _gaq=[['_setAccount','<?php echo config_item('google_analytics_id'); ?>'],['_trackPageview']];
	    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	    g.src='//www.google-analytics.com/ga.js';
	    s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>

<?php endif; ?>


</body>
</html>
