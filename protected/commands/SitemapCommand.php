<?php

/**
 * Runs the sitemap Generator
 *
 * @author Mateusz Piatkowski 2015 https://github.com/renegat59
 */
class SitemapCommand extends CConsoleCommand {
	public $domain;
	public $languages;

	public function actionGenerate(){
		$this->domain = 'https://'.BASE_DOMAIN;
		Yii::app()->getModule('material');
		$this->languages = Language::model()->findAll();

		foreach($this->languages as $language){
			$locale_domain = $this->getLocaleDomain($language->slug);
			echo("Starting crawler for {$language->slug} language.\r\n");
			Yii::app()->sitemap->clear();
			$filename = str_replace('http://', '', $this->domain);
			$filename = str_replace('https://', '', $filename);
			$filename = str_replace('.', '-', $filename);
			$filename = "sitemap-{$filename}-{$language->slug}.xml";

			Yii::app()->sitemap->sitemapPath = Yii::getPathOfAlias('application').'/runtime/sitemap/';
			Yii::app()->sitemap->sitemapName = $filename;
			Yii::app()->sitemap->websiteAddress = $locale_domain;
			Yii::app()->sitemap->excludeRegex = array(
				'/^(.*)(mailto:)(.*)$/', //dont include email links
				'/^(.*)(site\/auth\/provider)(.*)$/', //dont include social auth links
				'/^(.*)(new\?page\=)(.*)$/', //dont include social auth links
				'/^(.*)(article\?page\=)(.*)$/', //dont include social auth links
				'/^(.*)(view\.php\?)(.*)$/', //dont include social auth links
				'/^(.*)(assets)(.*)$/', //dont include social auth links

				"/^(.*)(kmb.php)(.*)$/",
				"/^(.*)(istor1.php)(.*)$/",
				"/^(.*)(bar.php)(.*)$/",
				"/^(.*)(kvest.php)(.*)$/",
				"/^(.*)(hunter.php)(.*)$/",
				"/^(.*)(e.biz)(.*)$/",
				"/^(.*)(train.php)(.*)$/",
				"/^(.*)(ap.php)(.*)$/",
				"/^(.*)(pustosh.php)(.*)$/",
				"/^(.*)(statistic.php)(.*)$/",
				"/^(.*)(armor.php)(.*)$/",
				"/^(.*)(weapons.php)(.*)$/",
				"/^(.*)(pshop.php)(.*)$/",
				"/^(.*)(inventory.php)(.*)$/",
				"/^(.*)(stats.php)(.*)$/",
				"/^(.*)(imarket.php)(.*)$/",
				"/^(.*)(implant.php)(.*)$/",
				"/^(.*)(library.php)(.*)$/",
				"/^(.*)(city.php)(.*)$/",
				"/^(.*)(donate.php)(.*)$/",
				"/^(.*)(tatoo.php)(.*)$/",
				"/^(.*)(battle.php)(.*)$/",
				"/^(.*)(lumbermill.php)(.*)$/",
				"/^(.*)(deizi.php)(.*)$/",
				"/^(.*)(home.php)(.*)$/",
				"/^(.*)(lekar.php)(.*)$/",
				"/^(.*)(food.php)(.*)$/",
				"/^(.*)(crafting.php)(.*)$/",
				"/^(.*)(recipe_shop.php)(.*)$/",
				"/^(.*)(bank.php)(.*)$/",
				"/^(.*)(pets.php)(.*)$/",
				"/^(.*)(clans.php)(.*)$/",
				"/^(.*)(chat.php)(.*)$/",
				"/^(.*)(clans.php)(.*)$/",
				"/^(.*)(idauction.php)(.*)$/",
				"/^(.*)(account.php)(.*)$/"
			);

			foreach (RNews::model()->current()->findAll() as $article){
				/** @var $article RNews */
				Yii::app()->sitemap->addLink($locale_domain.$article->getURL(), 2);
			}

			Yii::app()->sitemap->generateSitemap();
		}
	}

	public function getLocaleDomain($locale){
		$url = $this->domain;
		$scheme = parse_url($url, PHP_URL_SCHEME);
		$path   = parse_url($url, PHP_URL_PATH);
		$query  = parse_url($url, PHP_URL_QUERY);
		$fragment = parse_url($url, PHP_URL_FRAGMENT);
		$domain = parse_url($url, PHP_URL_HOST);
		$chains = explode('.', $domain);
		$subdomain = isset($chains[0])?$chains[0]:null;

		if (strlen($subdomain) == 2) {
			unset($chains[0]);
		}

		if ($locale != Yii::app()->language) {
			$domain = $locale.'.'.$domain;
		}

		$url = $scheme?$scheme:'http';
		$url .= "://";
		$url .= $domain;
		$url .= $path?$path:'';
		$url .= $query?"?".$query:'';
		$url .= $fragment?"#".$fragment:'';
		return $url;
	}

}
