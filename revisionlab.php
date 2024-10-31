<?php
/*
Plugin Name: RevisionLab - Feedback Manager
Plugin URI: http://revisionlab.com/site/wordpress-plugin/
Description: RevisionLab helps you manage web development projects feedbackand changes.
Version: 0.1
Author: Revision Lab
Author URI: http://revisionlab.com
License: GPL2
*/
?>


<?php

/**
 * Code that actually inserts stuff into pages.
 */
if ( !class_exists('RevisionLab') ) {

	class RevisionLab {
	
		var $optionname = 'RevisionLab';
		var $hidden_field = 'apicode-submit';
		
		/**
		 * PHP4 Constructor
		 */
		function RevisionLab() {
			$this->__construct();
		}
		
		/**
		 * Constructur, load all required stuff.
		 */
		function __construct() {
			add_action('admin_menu', array(&$this,'my_plugin_menu') );
			add_action('wp_footer', array(&$this,'insert_code'));
		}
		
		function insert_code(){
			$options = get_option( $this->optionname );
			
			if( $options['apienabled'] == 'true' && !empty($options['apicode']) ){
				?>
				
				<script type="text/javascript">

				  var _inc = _inc || [];
				  _inc.push(['_setAccount', '<?php echo $options['apicode']; ?>']);
				
				  (function() {
				    var ins = document.createElement('script'); ins.type = 'text/javascript';
				    ins.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.revisionlab.com/in.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ins, s);
				  })();
				
				</script>
				
				<?php
			}
		}
		
		function config_page() {

			if( isset($_POST[ $this->hidden_field ]) && $_POST[ $this->hidden_field ] == 'Y' ) {
			
				$options = array(
					'apicode' => $_POST[ 'apicode' ],
					'apienabled' => $_POST[ 'apienabled' ]
				);
				update_option($this->optionname, $options);
				
				?>
				
				<div id="message" class="updated fade">
					<p><strong>
				    <?php _e('Options saved.', 'att_trans_domain' ); ?>
				    </strong></p>
				</div>
				
				<?php
			
			}

			$options = get_option( $this->optionname );
			if( $options['apienabled'] == 'true' ){
				$enabled = 'checked="checked"';
			}else{
				$disabled = 'checked="checked"';
			}
			
			?>
			<div class="wrap">
				<h2><?php _e("RevisionLab Configuration") ?></h2>
			</div>
			
			<form name="rl_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  				<input type="hidden" name="<?php echo $this->hidden_field; ?>" value="Y">

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="blogname">Enable</label></th>
							<td>
								<input type="radio" value="true" id="apienabled" name="apienabled" <?php echo $enabled; ?> > Enabled &nbsp; &nbsp;
								<input type="radio" value="false" id="apienabled" name="apienabled" <?php echo $disabled; ?> > Disabled
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="blogname">API Code</label></th>
							<td><input type="text" class="regular-text" value="<?php echo $options['apicode']; ?>" id="apicode" name="apicode"></td>
						</tr>
					</tbody>
				</table>
			<br />
			
			<div class="alignleft">
				<input type="submit" class="button-primary" name="submit" value="<?php echo __('Update RevisionLab Settings &raquo;'); ?>" />
				</div>
			<br class="clear"/>
			
			</form>
			<?php
		}
		
		function my_plugin_menu() {
			add_options_page('RevisionLab Options', 'RevisionLab', 'manage_options', 'revision-lab', array(&$this, 'config_page'));
		}
		
	}
	
}

$rl = new RevisionLab();
	
?>

