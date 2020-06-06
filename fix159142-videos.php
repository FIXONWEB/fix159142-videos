<?php
/**
 * Plugin Name:     Fixonweb Vídeos
 * Plugin URI:      https://fixonweb.com.br/plugin/fix159142-videos
 * Description:     Ref: 159142 - Exibição de vídeos
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix159142
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Fix159142
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://github.com/fixonweb/fix159142-videos',__FILE__, 'fix159142-videos/fix159142-videos');

add_action('wp_enqueue_scripts', "fix159142_enqueue_scripts");
function fix159142_enqueue_scripts(){
    wp_enqueue_script( 'start-jquery', plugin_dir_url( __FILE__ ). '/js/start-jquery.js', array( 'jquery' )  );
}

add_action('wp_head', 'fix159142_wp_head');
function fix159142_wp_head(){
	?>
	<style type="text/css">
		html {
  			min-height: 100%;
		}
		body {
  			min-height: 100%;
		}
		#page {
     		position: inherit!important; 
     		/*min-height: 100%;*/
		}
		#fixfooter {
  			position: fixed;
  			right: 0;
  			bottom: 0;
  			left: 0;
  			display: none;
		}
	</style>
	<script type="text/javascript">
		jQuery(function($){
			$('#fixfooter').css('display','block');
		});
	</script>
	<?php
}

add_shortcode("fix159142_youtube_grid_playlist", "fix159142_youtube_grid_playlist");
function fix159142_youtube_grid_playlist($atts, $content = null){
	extract(shortcode_atts(array(
		"cols" => '3',
		"rows" => '3',
		"limit" => '9',
		"item_height" => '150px',
		"item_row1_size" => '70%',
		"item_row2_height" => '30%',
		"font_size" => '14px'
	), $atts));

	$fix159142S_option = get_option('fix159142S_option');
	$API_key = $fix159142S_option['fix159142S_api_key'];
	$playlist_id = $fix159142S_option['fix159142S_playlist_id'];

	if(!$API_key) return 'Antes de exibir, por favor, configure o plugin';
	if(!$playlist_id) return 'Antes de exibir, por favor, configure o plugin';
	
	$api_url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults='.$limit.'&playlistId='. $playlist_id . '&key=' . $API_key;
	$videoList = json_decode(file_get_contents($api_url));
	ob_start();
	?>
	<style type="text/css" media="screen">
		.fix159142_vd_main {
			display: grid;
			grid-template-columns: repeat(<?php echo $cols ?>, 1fr);
			grid-template-rows: repeat(<?php echo $rows ?>, <?php echo $item_height ?>);
			grid-column-gap: 10px;
			grid-row-gap: 10px;
			background-color: black;
		}
		.fix159142_vd_main .youtube-video {
			border: 0px solid gray;
		}
		.fix159142_vd_main .row1 {
			height: <?php echo $item_row1_size ?>;
		}
		.fix159142_row2 {
			color: white;
			background: rgba(0, 0, 0, 0.6);
			padding: 10px;
			line-height: 1; 
			height: <?php echo $item_row2_height ?>;
			font-size: <?php echo $font_size ?>;
			text-align: center;
		}
		.fix159142_row2 a {
			color: white;
		}
	</style>

	<div class="fix159142_vd_main">
		<?php foreach($videoList->items as $item){ ?>
			<div class="youtube-video" style="background-color: black; background-size: cover; background-repeat: no-repeat; background-image: url(<?php echo $item->snippet->thumbnails->medium->url ?>);">
				<div class="row1"></div>
				<div class="fix159142_row2">
					<?php $url_video = "https://youtu.be/".$item->snippet->resourceId->videoId ?>
					<a href="<?php echo $url_video ?>" target="_blank" title=""><?php echo $item->snippet->title ?></a>
				</div>
				</div>
		<?php } ?>
	</div>

	<?php
	return ob_get_clean();
}



class Fix159134SettingsPage {
	/*
	ref: https://codex.wordpress.org/Creating_Options_Pages#Example_.232
	*/
    private $options;
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    public function add_plugin_page() {
        add_options_page(
            'Settings Admin', 
            'Fix Youtube Grid Playlist', 
            'manage_options', 
            'fix159142S_setting_admin', 
            array( $this, 'create_admin_page' )
        );
    }
    public function create_admin_page() {
        $this->options = get_option( 'fix159142S_option' );
    	?>
		    <div class="wrap">
		        <?php screen_icon(); ?>
		        <h2>Plugin fix-list-youtube</h2>           
		        <form method="post" action="options.php">
		        <?php
		            settings_fields( 'fix159142S_option_group' );   
		            do_settings_sections( 'fix159142S_setting_admin' );
		            submit_button(); 
		        ?>
		        </form>
		    </div>
    	<?php
    }
    public function page_init() {
    	register_setting('fix159142S_option_group','fix159142S_option',array( $this, 'sanitize' ));
        add_settings_section('setting_section_id','Configurações',array( $this, 'print_section_info' ), 'fix159142S_setting_admin' );  
        add_settings_field( 'fix159142S_api_key', 'API Key', array( $this, 'fix159142S_api_key_callback' ), 'fix159142S_setting_admin', 'setting_section_id'); 
        add_settings_field( 'fix159142S_playlist_id', 'Playlist ID', array( $this, 'fix159142S_playlist_id_callback' ), 'fix159142S_setting_admin', 'setting_section_id'); 
    }
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['fix159142S_api_key'] ) ) $new_input['fix159142S_api_key'] = sanitize_text_field( $input['fix159142S_api_key'] );
        if( isset( $input['fix159142S_playlist_id'] ) ) $new_input['fix159142S_playlist_id'] = sanitize_text_field( $input['fix159142S_playlist_id'] );
        return $new_input;
    }
    public function print_section_info() {print 'Para perfeito funcionamento do plugin, informa as configurações abaixo:';}
    public function fix159142S_api_key_callback() {printf('<input type="text" id="fix159142S_api_key" name="fix159142S_option[fix159142S_api_key]" value="%s" />', isset( $this->options['fix159142S_api_key'] ) ? esc_attr( $this->options['fix159142S_api_key']) : '' );}
    public function fix159142S_playlist_id_callback() {printf('<input type="text" id="fix159142S_playlist_id" name="fix159142S_option[fix159142S_playlist_id]" value="%s" />', isset( $this->options['fix159142S_playlist_id'] ) ? esc_attr( $this->options['fix159142S_playlist_id']) : '' );}
}
if( is_admin() ) $Fix159134_settings_page = new Fix159134SettingsPage();

include "post-types/playlist.php";
