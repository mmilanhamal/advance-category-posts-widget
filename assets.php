<?php
/**
* All the functional part of the plugin
*/
class Apcw_Plugin_Assets
{
	
	function init()
	{
		add_image_size( 'post-slider-thumb-size', 330, 190, true ); 
		add_action( 'wp_enqueue_scripts', array( $this, 'apcw_enqueue_front_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'apcw_enqueue_backend_assets' ) );
	}
	
	/*
	* All the required assets enqueue
	*
	*/
	function apcw_enqueue_front_assets()
	{
    wp_enqueue_style( 'front', plugin_dir_url( __FILE__ ). 'assets/front.css', array(), '1.0');
	  wp_enqueue_style( 'dashicons' );
  	wp_enqueue_style( 'owl-carousel', plugin_dir_url( __FILE__ ). 'assets/owl.carousel.min.css', array(), '1.0');
		wp_enqueue_script( 'owl-carousel', plugin_dir_url( __FILE__ ) . 'assets/owl.carousel.min.js', array('jquery'), '1.0.0', true );
	}

	/*
	* All the required assets enqueue
	*
	*/
	function apcw_enqueue_backend_assets()
	{
		wp_enqueue_script( 'apcw-custom', plugin_dir_url( __FILE__ ) . 'assets/custom.js', array('jquery'), '1.0.0', true );
		wp_enqueue_style( 'owl-carousel', plugin_dir_url( __FILE__ ). 'assets/custom.css', array(), '1.0');
	}

	/*
	* Function to minify js
	*
	*/
	function apcw_minify_js( $input ) {
      if(trim($input) === "") return $input;
      return preg_replace(
          array(
              // Remove comment(s)
              '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
              // Remove white-space(s) outside the string and regex
              '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
              // Remove the last semicolon
              '#;+\}#',
              // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
              '#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',
              // --ibid. From `foo['bar']` to `foo.bar`
              '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',
              // Replace `true` with `!0`
              '#(?<=return |[=:,\(\[])true\b#',
              // Replace `false` with `!1`
              '#(?<=return |[=:,\(\[])false\b#',
              // Clean up ...
              '#\s*(\/\*|\*\/)\s*#'
          ),
          array(
              '$1',
              '$1$2',
              '}',
              '$1$3',
              '$1.$3',
              '!0',
              '!1',
              '$1'
          ),
      $input);
  	}

  	/*
	* Function to minify CSS
	*
	*/
  	function apcw_minify_css( $input ) {
      if(trim($input) === "") return $input;
      // Force white-space(s) in `calc()`
      if(strpos($input, 'calc(') !== false) {
          $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
              return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
          }, $input);
      }
      return preg_replace(
          array(
              // Remove comment(s)
              '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
              // Remove unused white-space(s)
              '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
              // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
              '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
              // Replace `:0 0 0 0` with `:0`
              '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
              // Replace `background-position:0` with `background-position:0 0`
              '#(background-position):0(?=[;\}])#si',
              // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
              '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
              // Minify string value
              '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
              '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
              // Minify HEX color code
              '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
              // Replace `(border|outline):none` with `(border|outline):0`
              '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
              // Remove empty selector(s)
              '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
              '#\x1A#'
          ),
          array(
              '$1',
              '$1$2$3$4$5$6$7',
              '$1',
              ':0',
              '$1:0 0',
              '.$1',
              '$1$3',
              '$1$2$4$5',
              '$1$2$3',
              '$1:0',
              '$1$2',
              ' '
          ),
      $input);
  	}
}
$obj = new Apcw_Plugin_Assets;
$obj->init();