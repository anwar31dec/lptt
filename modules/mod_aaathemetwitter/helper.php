<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_twitter
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modAAAthemeTwitterHelper {
	/*************************************** config ***************************************/
	private $twittername;

   // Your Twitter App Consumer Key
	private $consumer_key;

	//test
	//private $consumer_key = $smof_data['twitter'];
	

	// Your Twitter App Consumer Secret
	private $consumer_secret;

	// Your Twitter App Access Token
	private $user_token;

	// Your Twitter App Access Token Secret
	private $user_secret;

	private $counts;

	// Path to tmhOAuth libraries
	private $lib = './modules/mod_aaathemetwitter/lib/';

	// Enable caching
	private $cache_enabled = true;

	// Cache interval (minutes)
	private $cache_interval = 15;

	// Path to writable cache directory
	private $cache_dir = './modules/mod_aaathemetwitter/cache/';

	// Enable debugging
	private $debug = true;



	/**************************************************************************************/

	public function __construct($params) {
		// Initialize paths and etc.
		$this->pathify($this->cache_dir);
		//echo $this->cache_dir;die;
		$this->pathify($this->lib);
		$this->message = '';

		$this->twittername = $params->get('twittername');
		$this->consumer_key = $params->get('consumer_key');
		$this->consumer_secret = $params->get('consumer_key_secret');
		$this->user_token = $params->get('access_token');
		$this->user_secret = $params->get('access_token_secret');
		$this->counts = $params->get('counts','1');
		// Set server-side debug params
		if($this->debug === true) {
			error_reporting(-1);
		} else {
			error_reporting(0);
		}

		return $this;
	}

	public function fetch() {
		// return json_encode(
		// 	array(
		// 		'response' => json_decode($this->getJSON(), true),
		// 		'message' => ($this->debug) ? $this->message : false
		// 	)
		// );
		return json_decode($this->getJSON(), true);
	}

	private function getJSON() {
		if($this->cache_enabled === true) {
			$CFID = $this->generateCFID();
			$cache_file = $this->cache_dir.$CFID;

			if(file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * intval($this->cache_interval)))) {
				return file_get_contents($cache_file, FILE_USE_INCLUDE_PATH);
			} else {

				$JSONraw = $this->getTwitterJSON();
				$JSON = $JSONraw['response'];

				// Don't write a bad cache file if there was a CURL error
				if($JSONraw['errno'] != 0) {
					$this->consoleDebug($JSONraw['error']);
					return $JSON;
				}

				if($this->debug === true) {
					// Check for twitter-side errors
					$pj = json_decode($JSON, true);
					if(isset($pj['errors'])) {
						foreach($pj['errors'] as $error) {
							$message = 'Twitter Error: "'.$error['message'].'", Error Code #'.$error['code'];
							$this->consoleDebug($message);
						}
						return false;
					}
				}

				if(is_writable($this->cache_dir) && $JSONraw) {
					if(file_put_contents($cache_file, $JSON, LOCK_EX) === false) {
						$this->consoleDebug("Error writing cache file");
					}
				} else {
					$this->consoleDebug("Cache directory is not writable");
				}
				return $JSON;
			}
		} else {
			$JSONraw = $this->getTwitterJSON();

			if($this->debug === true) {
				// Check for CURL errors
				if($JSONraw['errno'] != 0) {
					$this->consoleDebug($JSONraw['error']);
				}

				// Check for twitter-side errors
				$pj = json_decode($JSONraw['response'], true);
				if(isset($pj['errors'])) {
					foreach($pj['errors'] as $error) {
						$message = 'Twitter Error: "'.$error['message'].'", Error Code #'.$error['code'];
						$this->consoleDebug($message);
					}
					return false;
				}
			}
			return $JSONraw['response'];
		}
	}

	private function getTwitterJSON() {
		require $this->lib.'tmhOAuth.php';
		require $this->lib.'tmhUtilities.php';

		$tmhOAuth = new tmhOAuth(array(
			'host'                  => 'api.twitter.com',//$_POST['request']['host'],
			'consumer_key'          => $this->consumer_key,
			'consumer_secret'       => $this->consumer_secret,
			'user_token'            => $this->user_token,
			'user_secret'           => $this->user_secret,
			'curl_ssl_verifypeer'   => false
		));

		//$url = $_POST['request']['url'];
		//$params = $_POST['request']['parameters'];
		$url = "/1.1/statuses/user_timeline.json";

		/*parameters: $.extend({}, defaults, {
						screen_name: s.username,
						page: s.page,
						count: count,
						include_rts: 1,
						include_entities: 1
					})*/
		$params = array('screen_name'=>$this->twittername, 'page'=>1,'count'=>$this->counts,'include_rts'=>1,'include_entities'=>1);

		$tmhOAuth->request('GET', $tmhOAuth->url($url), $params);
		return $tmhOAuth->response;
	}

	private function generateCFID() {
		// The unique cached filename ID
		return md5(serialize($_POST)).'.json';
	}

	private function pathify(&$path) {
		// Ensures our user-specified paths are up to snuff
		$path = realpath($path).'/';
	}

	private function consoleDebug($message) {
		if($this->debug === true) {
			$this->message .= 'tweet.js: '.$message."\n";

		}
	}

	/*
		* Prepare feeds
		*/			
		public function prepareTweet($string) {
			
			//Url
			$pattern = '/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i';
			$replacement = '<a class="tweet_url" href="$1">$1</a>';
			$string = preg_replace($pattern, $replacement, $string);

			//Search
				$pattern = '/[\#]+([A-Za-z0-9-_]+)/i';
				$replacement = ' <a  class="tweet_search" href="http://search.twitter.com/search?q=$1">#$1</a>';
				$string = preg_replace($pattern, $replacement, $string);
		
/*
			//Mention
			if ($this->params->get('linked_mention')==1) {
				$pattern = '/\s[\@]+([A-Za-z0-9-_]+)/i';
				$replacement = ' <a  class="tweet_mention" href="http://twitter.com/$1">@$1</a>';
				$string = preg_replace($pattern, $replacement, $string);	
			}

			//Mention
			if ($this->params->get('email_linked')==1) {
				$pattern = '/\s([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})/i';
				$replacement = ' <a class="tweet_email" href="mailto:$1">$1</a>';
				$string = preg_replace($pattern, $replacement, $string);
			}*/
			return $string;
		}

	//Function for converting time
		public function timeago($timestamp) {
			$time_arr 		= explode(" ",$timestamp);
			$year 			= $time_arr[5];
			$day 			= $time_arr[2];
			$time 			= $time_arr[3];
			$time_array 	= explode(":",$time);
			$month_name 	= $time_arr[1];
			$month = array (
				'Jan' => 1,
				'Feb' => 2,
				'Mar' => 3,
				'Apr' => 4,
				'May' => 5,
				'Jun' => 6,
				'Jul' => 7,
				'Aug' => 8,
				'Sep' => 9,
				'Oct' => 10,
				'Nov' => 11,
				'Dec' => 12
			);

			$delta = gmmktime(0, 0, 0, 0, 0) - mktime(0, 0, 0, 0, 0);
			$timestamp = mktime($time_array[0], $time_array[1], $time_array[2], $month[$month_name], $day, $year);
			$etime = time() - ($timestamp + $delta);
			if ($etime < 1) {
				return '0 seconds';
			}

			$a = array( 12 * 30 * 24 * 60 * 60  =>  'YEAR',
				30 * 24 * 60 * 60       =>  'MONTH',
				24 * 60 * 60            =>  'DAY',
				60 * 60                 =>  'HOUR',
				60                      =>  'MINUTE',
				1                       =>  'SECOND'
			);

			foreach ($a as $secs => $str) {
				$d = $etime / $secs;
				if ($d >= 1) {
					$r = round($d);
					return $r . ' ' . JText::_($str . ($r > 1 ? 'S' : ''));
				}
			}
		}

		public function getLC3Date($timestamp){
			$format = JText::_('DATE_FORMAT_LC3');
			$created_string = trim(JHTML::_('date', $timestamp, $format));

			return $created_string;
		}
}
