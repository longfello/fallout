<?php

/**
 * SitemapComponent is creating the website sitemap by scrapping the website online.
 * It can be CRONed on any schedule by the SitemapCommand
 *
 * @author Mateusz Piatkowski 2015 https://github.com/renegat59
 */

Yii::import('application.vendors.*');
require_once('php-dom/simple_html_dom.php');

class SitemapComponent extends CApplicationComponent {

	/**
	 * Here we can define the URLs that should be excluded from the sitemap
	 * @var string[]
	 */
	public $exclude = array();

	/**
	 * Here we can define the Regular Expressions according to which the addresses
	 * will be filtered. In other words, if the address matching the regex from this array
	 * it will NOT appear in the sitemap
	 * @var string[]
	 */
	public $excludeRegex = array();

	private $excludeRegexDefault = array(
		'/^(.*)(mailto:)(.*)$/', //dont include email links
	);

	/**
	 * We can define wether the websites will be starting with http or https.
	 * Default is http://
	 * @var string
	 */
	public $protocolPattern = 'https';

	/**
	 * Path where the sitemap should be stored. It's obligatory.
	 * @var string
	 */
	public $sitemapPath;

	/**
	 * File name of the sitemap file. Default is sitemap.xml
	 * @var string
	 */
	public $sitemapName = 'sitemap.xml';

	/**
	 * Here we can override the website address that we want to be scrapped.
	 * By default we take address from <b>Yii::app()->urlManager->baseUrl</b>
	 * @var type
	 */
	public $websiteAddress = null;

	/**
	 * Defines how deep into the website structure we should go
	 * @var integer
	 */
	public $maxLevels = 5;

	/**
	 * Defines change frequecy of the website. Default - weekly
	 * @var string
	 */
	public $changefreq = 'weekly';

	/**
	* If set to <b>FALSE</b>, date of last modification will be set to current date.
	* If set to <b>TRUE</b>, the script will try to get last modification from the server.
	* @var boolean
	*/
	public $lastModFromServer = true;

	/**
	* Determines wether we should add <b>lastmod</b> tag in the sitemap
	* @var boolean
	*/
	public $addLastmod = true;

	/**
	 * Here we'll collect the sitemap links
	 * @var SitemapLink[]
	 */
	private $visitedLinks = array();

	/**
	 * List of links waiting to be visited
	 * @var SitemapLink[]
	 */
	private $waitingLinks = array();

	private $configChecked = false;

	const SITEMAP_HEADER = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
		http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
	const FILETIME = 'FILETIME';

	public function init() {
		parent::init();
		$this->excludeRegex = array_merge(
			$this->excludeRegexDefault,
			$this->excludeRegex);
		$this->sitemapPath = rtrim($this->sitemapPath);
	}

	public function clear(){
		$this->visitedLinks = array();
		$this->waitingLinks = array();
	}

	/**
	 * This method generates the sitemap and saves in the selected destination file.
	 * @return boolean
	 * @throws Exception Throws exception if the configuration is wrong
	 */
	public function generateSitemap(){
		$this->checkConfig();
		$this->generateLinks();

		$sitemapFile = $this->sitemapPath.'/'.$this->sitemapName;
		$sitemapContent = self::SITEMAP_HEADER."\r\n";
		foreach ($this->visitedLinks as $sitemapLink){
			$sitemapContent .= '<url>'
				. '<loc>'.$this->escapeUrl($sitemapLink->url).'</loc>'
				. $this->getLastMod($sitemapLink)
				. '<changefreq>'.$this->changefreq.'</changefreq>'
				. '<priority>'.number_format($sitemapLink->priority, 2).'</priority>'
				. '</url>'."\r\n";
		}
		$sitemapContent .= '</urlset>';
		$sitemapSaved = file_put_contents($sitemapFile, $sitemapContent);
		if($sitemapSaved === FALSE){
			throw new Exception('Sitemap saving to location"'.$sitemapFile.'" failed!');
		}
		return true;
	}

	private function generateLinks(){
		$this->checkConfig();
		$this->websiteAddress = $this->websiteAddress ? $this->websiteAddress : Yii::app()->urlManager->baseUrl;
		$this->websiteAddress = $this->cleanUrl($this->websiteAddress);
		$this->queue($this->websiteAddress, 0);
		while(!empty($this->waitingLinks)){
			$this->readLink();
		}
		return $this->visitedLinks;
	}

	/**
	 * This will take all the links from the website and save it in
	 * $this->sitemapLinks.
	 */
	private function readLink(){
		$sitemapLink = $this->nextLink();
		//we have to quit the recurrsion at some point:
		if($sitemapLink === FALSE){
			//if there is no links, return
			return;
		}

		$fileinfo = array();

		$pageHtml = $this->getSite($sitemapLink->url, $fileinfo);
		if($pageHtml !== null){
			$dom = str_get_html($pageHtml);
			if($dom !== null) {
				//set as visited, add to sitemap, set lastmod:
				$lastmod = $fileinfo[self::FILETIME] !== -1 ? date('c', $fileinfo[self::FILETIME]) : null;
				$this->visit($sitemapLink->url, $lastmod);
				//and check for another links:
				if ($dom) {
					$links = $dom->find('a');
					foreach($links as $link){
						$linkUrl = $this->cleanUrl($link->href);
						if($this->isOurUrl($linkUrl) &&
						   $this->filter($linkUrl) &&
						   $sitemapLink->level<$this->maxLevels){
							//add for scanning only OUR links:
							$this->queue($linkUrl, $sitemapLink->level+1);
						}
					}
				}
			} else {
				//non parsable link
				$this->dequeue($sitemapLink->url);
			}
		} else {
			//some error like 404 or sth, we dequeue without checking again:
			$this->dequeue($sitemapLink->url);
		}
	}

	private function applyProtocol($url){
		if($this->protocolPattern == 'http'){
			if(strpos($url, 'https://') === 0){
				$url = str_replace('https://', 'http://', $url);
			}
		}
		if($this->protocolPattern == 'https'){
			if(strpos($url, 'http://') === 0){
				$url = str_replace('http://', 'https://', $url);
			}
		}
		return $url;
	}

	/**
	 * Moves the link from waiting links to visited links
	 * @param string $link
	 */
	private function visit($link, $lastmod=null){
		if(isset($this->waitingLinks[$link])){
			$sitemapLink = $this->waitingLinks[$link];
			unset($this->waitingLinks[$link]);
			if(!isset($this->visitedLinks[$link])){
				$sitemapLink->lastmod = $lastmod;
				$this->visitedLinks[$link] = $sitemapLink;
			}
		}
	}

	/**
	 * Add the link to the visit queue
	 * @param string $link
	 */
	private function queue($link, $level){
		//if the link is not queued or visited, we queue it:
		if(!isset($this->waitingLinks[$link]) && !isset($this->visitedLinks[$link])){
			echo('adding link '.$link."\r\n");
			$priority = $level == 0 ? 1 : ($level == 1 ? 0.9 : (0.3+1/(1+$level)));
			$this->waitingLinks[$link] = new SitemapLink($link, $level, $priority);
		}
	}

	public function addLink($link, $level){
		echo('manual adding link '.$link."\r\n");
		$priority = $level == 0 ? 1 : ($level == 1 ? 0.9 : (0.3+1/(1+$level)));
		$this->visitedLinks[$link] = new SitemapLink($link, $level, $priority);
	}

	private function dequeue($link){
		if(isset($this->waitingLinks[$link])){
			unset($this->waitingLinks[$link]);
		}
	}

	/**
	 * Checks if the URL is "our", means, if it comes from our domain
	 * @param string $link
	 * @return boolean
	 */
	private function isOurUrl($link){
		//check if the link starts with our website address
		//all the addresses are "cleaned" so they are also lower case.
		//thanks to this we can compare them easily
		return strpos($link, $this->websiteAddress) === 0;
	}

	/**
	 * Checks if the url matches the filters. Returns TRUE if it matches and FALSE
	 * if it should be excluded.
	 * @param string $url
	 * @return boolean
	 */
	private function filter($url){
		//check the normal excludes
		if(in_array($url, $this->exclude)){
			return false;
		}
		//check the regex excludes:
		foreach($this->excludeRegex as $regex){
			//first that matches excludes fomr sitemap
			if(preg_match($regex, $url) == 1){
				return false;
			}
		}
		return true;
	}

	/**
	 * Gets the next link to be visited from visit queue
	 * If queue is empty returns FALSE
	 * @return SitemapLink the next sitemap link to visit
	 */
	private function nextLink(){
		return reset($this->waitingLinks);
	}

	/**
	 * This function modifies URL to the format we want to have in the sitemap
	 * @param string $url
	 */
	private function cleanUrl($url){
		$cleanUrl = $url;
		if(strpos($url, '//') === 0){
			//the url starts with the //, we just add the protocol:
			$cleanUrl = $this->protocolPattern.':'.$url;
		}
		elseif(strpos($url, 'http') === 0){
			//it's a full link, we just clean it:
			$cleanUrl = $this->applyProtocol($url);
		} else {
			//any other option, we leave the bare domain and merge base with ending
			//with a slash in the middle:
			$baseUrl = parse_url($this->websiteAddress, PHP_URL_HOST);
			$cleanUrl = $this->protocolPattern.'://'.$baseUrl . '/' . ltrim($url, '/');
		}
		return strtolower(rtrim($cleanUrl, '#/'));
	}

	private function getSite($url, &$fileinfo=array()){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_FILETIME, TRUE);
		$data = curl_exec($ch);

		if($this->addLastmod && $this->lastModFromServer){
			$filetime = curl_getinfo($ch, CURLINFO_FILETIME);
			$fileinfo[self::FILETIME] = $filetime;
		}
		curl_close($ch);
		return $data === FALSE ? null : $data;
	}

	private function getLastMod($sitemapLink){
		if($this->addLastmod == false || $sitemapLink == null){
			return '';
		}
		if($sitemapLink->lastmod !== null && $this->lastModFromServer){
			return '<lastmod>'.$sitemapLink->lastmod.'</lastmod>';
		} else {
			return '<lastmod>'.date('c').'</lastmod>';
		}
	}

	private function escapeUrl($url){
		//remove special chars
		$url = str_replace(array("%3A", "%2F", "%23"),array(":", "/","#"), $url);
		//and URL encode for XML 1.0
		$url = htmlspecialchars($url, ENT_QUOTES|ENT_XML1);
		return $url;
	}

	private function checkConfig(){
		if(!$this->configChecked){
			if($this->protocolPattern !== 'http' && $this->protocolPattern !== 'https'){
				throw new Exception('Protocol Pattern wrong. Set "http" or "https".');
			}
			if(!file_exists($this->sitemapPath) || !is_dir($this->sitemapPath)){
				throw new Exception('The selected path doesnt exists or is not a directory: '.$this->sitemapPath);
			}
			if(empty($this->sitemapName)){
				throw new Exception('The sitemap file name must be specified.');
			}
		}
		$this->configChecked = true;
	}
}

class SitemapLink {
	public $url;
	public $priority;
	public $level;

	public function __construct($url, $level, $priority=1){
		$this->url = $url;
		$this->level = $level;
		$this->priority=$priority;
	}
}
