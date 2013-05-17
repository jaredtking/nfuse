<?php
/**
 * @package nFuse
 * @author Jared King <j@jaredtking.com>
 * @link http://jaredtking.com
 * @version 1.0
 * @copyright 2013 Jared King
 * @license MIT
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
	associated documentation files (the "Software"), to deal in the Software without restriction,
	including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
	and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
	subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or
	substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
	LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
	SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace nfuse;

require_once "libs/Smarty/Smarty.class.php";

class ViewEngine Extends \Smarty
{
	var $base_template_dir;
	var $admin_template_dir;
	var $functions_dir;
	
	private static $engine;
	
	/*
	* Constructor
	*/
	function __construct($options = array())
	{
		parent::__construct();
		
		$this->error_reporting = 1;
		$this->base_template_dir = NFUSE_BASE_DIR . '/templates';
		$this->template_dir = $this->base_template_dir . '/';
		$this->compile_dir = NFUSE_TEMP_DIR . '/smarty/';
		$this->cache_dir = NFUSE_TEMP_DIR . '/smarty/cache/';
		$this->functions_dir = NFUSE_BASE_DIR . '/libs/Smarty/functions';
        $this->assign('app_name', SITE_TITLE);
        
        if( isset( $options[ 'nocache' ] ) )
		{
			// turn off caching
	        $this->caching = 0;
        	$this->force_compile = true;
	        $this->compile_check = true;
		}
		else
		{
			// turn on caching
			//$this->setCaching( Smarty::CACHING_LIFETIME_CURRENT);
			//$this->compile_check = false;
        }
		
		// minify CSS
		$this->autoCompileLess( NFUSE_BASE_DIR . '/css/styles.less', 'styles.css');
		
		// minify JS
		$this->autoCompileJs( NFUSE_BASE_DIR . '/js', 'header.js' );
	}
	
	function autoCompileLess( $inputFile, $outputFileName )
	{
		$cacheFile = NFUSE_TEMP_DIR . '/css/' . $outputFileName . ".cache";
		
		$outputFile = NFUSE_APP_DIR . '/css/' . $outputFileName;
		
		// load the cache
		if( file_exists( $cacheFile ) ) {
			$cache = unserialize( file_get_contents( $cacheFile ) );
		} else {
			$cache = $inputFile;
		}
		
		$less = new \nfuse\lessc;
		try
		{
			$newCache = $less->cachedCompile($cache);
			
			if( !is_array( $cache ) || $newCache[ 'updated' ] > $cache[ 'updated' ] ) {
				file_put_contents( $cacheFile, serialize( $newCache ) );
				file_put_contents( $outputFile, $newCache[ 'compiled' ] );
			}
		}
		catch( \Exception $ex )
		{
			echo "lessphp fatal error: " . $ex->getMessage();
		}
	}
	
	function autoCompileJs( $jsDirectory, $outputFileName )
	{
		// NOTE js files get appended in order by filename
		// to change the order of js files, change the filename
		
		$cacheFile = NFUSE_TEMP_DIR . '/js/' . $outputFileName . ".cache";
		
		$outputFile = NFUSE_APP_DIR . '/js/' . $outputFileName;

		$cache = false;
		if( file_exists( $cacheFile ) ) {
			$cache = unserialize( file_get_contents( $cacheFile ) );
		}

		$jsFiles = glob( $jsDirectory . '/*.js' );

		$newCache = array(
			'md5' => $this->md5OfDir( $jsFiles ),
			'production' => Config::value( 'site', 'production-level' ) );

		if( !is_array( $cache ) || $newCache[ 'md5' ] != $cache[ 'md5' ] || $newCache[ 'production' ] != $cache[ 'production' ] ) {
			// concatenate the js for every file
			$js = '';
			foreach( $jsFiles as $file ) {
				$js .= file_get_contents( $file ) . "\n";
			}
			
			// minify js in production mode
			if( Config::value( 'site', 'production-level' ) ) {
				$js = JSMin::minify( $js );
			}
			
			// write the js and cache to the output file
			file_put_contents( $cacheFile, serialize( $newCache ) );
			file_put_contents( $outputFile, $js );
		}
	}
	
	function assignData( $data )
	{
		foreach( $data as $key => $value )
			$this->assign( $key, $value );	
	}
	
	static function engine()
	{
		if( !self::$engine )
			self::$engine = new self();
				
		return self::$engine;
	}
		
	private function md5OfDir( $files )
	{
		$ret = '';
		foreach( $files as $filename )
		{
			if( $filename != '.' && $filename != '..' )
			{
				$filetime = filemtime( $filename );
				if( $filetime === false )
					return false;
				$ret .= date( "YmdHis", $filetime ) . basename( $filename );
			}
		}
		
		return md5($ret);
	}	
}