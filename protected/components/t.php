<?php

/**
 * Created by PhpStorm.
 * User: miloslawsky
 * Date: 27.11.15
 * Time: 10:45
 */
class t {

	const ESCAPE_BOTH = 1;
	const ESCAPE_SINGLE = 2;
	const ESCAPE_DOUBLE = 3;
	const ESCAPE_AS_HTML = 4;

	const MODEL_GAME = 'game';
	const MODEL_HOME = 'home';

	/** @var Language */
	public $language;
	/** @var Language[] */
	public $languages;

	private $languageModelSlug = self::MODEL_GAME;

	private static $controllerModel;
	private static $instance = array();
	private static $__sesstion_slug = 'user_language';

	protected function __construct( $langModel ) {
		$this->languageModelSlug = $langModel;
		$this->setLanguages();
		$this->setLanguage( false );
	}

	/** @return LanguageTranslate | LanguageTranslateHome */
	private function getLanguageModel( $layout = false ) {
		$layout = $layout ? $layout : $this->languageModelSlug;
		switch ( $layout ) {
			case self::MODEL_GAME:
				return LanguageTranslate::model();
				break;
			case self::MODEL_HOME:
				return LanguageTranslateHome::model();
				break;
			default:
				trigger_error( "Undefined language model: " . $layout );
		}
	}

	private function setLanguages() {
		$this->languages = array();
		$criteria        = new CDbCriteria();
		$criteria->order = "id=" . Language::DEFAULT_LANGUAGE . " DESC, sort_order ASC, name ASC";
		$models          = array();
		switch ( $this->languageModelSlug ) {
			case self::MODEL_GAME:
				$criteria->addCondition( 'enable_game = 1' );
				$models = Language::model()->cache( 3600 )->findAll( $criteria );
				break;
			case self::MODEL_HOME:
				$criteria->addCondition( 'enable_home = 1' );
				$models = Language::model()->cache( 3600 )->findAll( $criteria );
				break;
		}
		foreach ( $models as $one ) {
			$this->languages[ $one->slug ] = $one;
		}
	}

	public function setLanguage( $lang_iso = false, $not_save = false ) {
		if ( ! $lang_iso ) {
			$this->trySetBySession();
			$this->trySetLanguageByBrowser();
			if ( ! $this->trySetByDomain() ) {
				if ( isset( $this->languages[ Yii::app()->language ] ) ) {
					// Yii default language
					$this->language = $this->languages[ Yii::app()->language ];
				} else {
					// First language
					$this->language = reset( $this->languages );
				}
			}
		} else {
			$this->language = Language::model()->cache( 3600 )->findByAttributes( array( 'slug' => $lang_iso ) );
			if ( ! $this->language ) {
				$this->language = $this->setLanguage();
			}
		}

		if ( Yii::app()->controller->module->id != 'admin' && ! $not_save && isset( Yii::app()->stat ) && Yii::app()->stat->model && Yii::app()->stat->model->lang_slug != $this->language->slug ) {
			//Yii::app()->stat->model->lang_slug = $this->language->slug;
			//Yii::app()->stat->model->save(false);
			Yii::app()->db->createCommand( "UPDATE `players` SET `lang_slug` = '" . $this->language->slug . "' WHERE id='" . Yii::app()->stat->id . "'" )->execute();
			Yii::app()->stat->model->refresh();
		}

		switch ( $this->languageModelSlug ) {
			case self::MODEL_GAME:
				if ( $this->language->enable_game == 0 ) {
					if ( $this->language->fallback ) {
						// Fallback language
						return $this->setLanguage( $this->language->fallback );
					} else {
						// Yii default language
						return $this->setLanguage( $this->languages[ Yii::app()->language ] );
					}
				}
				break;
			case self::MODEL_HOME:
				if ( $this->language->enable_home == 0 ) {
					if ( $this->language->fallback ) {
						// Fallback language
						return $this->setLanguage( $this->language->fallback );
					} else {
						// Yii default language
						return $this->setLanguage( $this->languages[ Yii::app()->language ] );
					}
				}
				break;
		}

		$this->remember_language( $this->language->id );

		return $this->language;
	}

	private function trySetByDomain() {
		if ( Yii::app() instanceof CConsoleApplication ) {
			return false;
		}
		$domain    = Yii::app()->getBaseUrl( true );
		$domain    = parse_url( $domain, PHP_URL_HOST );
		$chains    = explode( '.', $domain );
		$subdomain = isset( $chains[0] ) ? $chains[0] : null;

		$language = reset( $this->languages );
		foreach ( $this->languages as $slug => $model ) {
			if ( $subdomain == $model->slug ) {
				$language = $model;
				break;
			}
		}
		$this->language = $language;

		return true;
	}

	private function trySetBySession() {
		if ( ! isset( Yii::app()->session ) ) {
			return false;
		}
		$user_lang = isset( Yii::app()->request->cookies['user_language'] ) ? Yii::app()->request->cookies['user_language']->value : false;

		if ( $user_lang && ! isset( Yii::app()->session[ self::$__sesstion_slug ] ) ) {
			$language = Language::model()->cache( 3600 )->findByPk( $user_lang );
			$this->remember_language( $language->id );
			Yii::app()->request->redirect( $this->getLocaleDomain( $language->slug ) );
			die();
		}

		return false;
	}

	private function trySetLanguageByBrowser() {
		if ( ! isset( Yii::app()->session ) ) {
			return false;
		}
		$CrawlerDetect = new CrawlerDetect;
		if ( $CrawlerDetect->isCrawler() ) {
			return false;
		}

		$user_lang = isset( Yii::app()->request->cookies['user_language'] ) ? Yii::app()->request->cookies['user_language']->value : false;

		if ( ! $user_lang && ! isset( Yii::app()->session[ self::$__sesstion_slug ] ) ) {
			if ( $lang_iso = $this->prefered_language( array_keys( $this->languages ) ) ) {
				$language = Language::model()->cache( 3600 )->findByAttributes( array( 'slug' => $lang_iso ) );
				$this->remember_language( $language->id );
				Yii::app()->request->redirect( $this->getLocaleDomain( $language->slug ) );
				die();
			}
		}

		return false;
	}

	final private function __clone() {
	}

	/**
	 * @return t
	 */
	public static function getInstance() {
		$langModel = self::MODEL_GAME;
		if (!self::$controllerModel) {
			if ( isset( Yii::app()->controller->langModel ) ) {
				$langModel = Yii::app()->controller->langModel;
			}
			self::$controllerModel = $langModel;
		} else {
			$langModel = self::$controllerModel;
		}
		if ( ! isset( self::$instance[ $langModel ] ) ) {
			self::$instance[ $langModel ] = new t( $langModel );
		}

		return self::$instance[ $langModel ];
	}

	public function getLocaleDomain( $locale = false ) {
		$locale = $locale ? $locale : $this->languages[ Yii::app()->language ]->slug;

		$url       = Yii::app()->getBaseUrl( true ) . Yii::app()->getRequest()->getUrl();
		$scheme    = parse_url( $url, PHP_URL_SCHEME );
		$path      = parse_url( $url, PHP_URL_PATH );
		$query     = parse_url( $url, PHP_URL_QUERY );
		$fragment  = parse_url( $url, PHP_URL_FRAGMENT );
		$domain    = parse_url( $url, PHP_URL_HOST );
		$chains    = explode( '.', $domain );
		$subdomain = isset( $chains[0] ) ? $chains[0] : null;

		if ( strlen( $subdomain ) == 2 ) {
			unset( $chains[0] );
		}
		$domain = implode( '.', $chains );

		if ( $locale != $this->languages[ Yii::app()->language ]->slug ) {
			$domain = $locale . '.' . $domain;
		}

		$url = $scheme ? $scheme : 'http';
		$url .= "://";
		$url .= $domain;
		$url .= $path ? $path : '';
		$url .= $query ? "?" . $query : '';
		$url .= $fragment ? "#" . $fragment : '';

		return $url;
	}

	public static function plural( $text, $number, $params = false ) {
		$translator = t::getInstance();
		$draft      = $translator->_get( $text );
		$forms      = explode( '|', $draft );
		$forms[]    = end( $forms );
		$forms[]    = end( $forms );
		$value      = $translator->_plural_form( $number, $forms );
		if ( $params ) {
			$value = vsprintf( $value, $params );
		}

		return $value;
	}

	public static function widget( $view = false ) {
		$view = $view ? $view : 'index';
		$ccc  = new CController( 'context' );
		$ccc->renderPartial( 'application.views.site.lang.' . $view, array(
			'lang' => t::getInstance(),
		) );
	}

	public static function iso() {
		return t::getInstance()->language->slug;
	}

	public static function encHtml( $text, $params = false ) {
		return htmlspecialchars( t::get( $text, $params ) );
	}

	public static function encJs( $text, $params = false, $escapeQuote = self::ESCAPE_AS_HTML ) {
		$text = str_replace( array( "\r", "\n" ), '', self::get( $text, $params ) );
		if ( $escapeQuote == self::ESCAPE_BOTH || $escapeQuote == self::ESCAPE_DOUBLE ) {
			$text = str_replace( '"', '\"', $text );
		}
		if ( $escapeQuote == self::ESCAPE_BOTH || $escapeQuote == self::ESCAPE_SINGLE ) {
			$text = str_replace( "'", "\'", $text );
		}
		if ( $escapeQuote == self::ESCAPE_AS_HTML ) {
			$text = htmlentities( $text, ENT_COMPAT, 'UTF-8' );
		}

		return $text;
	}

	public static function encSQL( $text, $params = false ) {
		return _mysql_real_escape_string( t::get( $text, $params ) );
	}

	private function _plural_form( $n, $forms ) {
		return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ( $n % 10 >= 2 && $n % 10 <= 4 && ( $n % 100 < 10 || $n % 100 >= 20 ) ? $forms[1] : $forms[2] );
	}

	public static function getDb( $field, $table, $key, $value, $params = false, $layout = self::MODEL_GAME ) {
		$text = t::getDbSlug( $field, $table, $key, $value );

		return self::get( $text, $params, $layout );
	}

	public static function getDbSlug( $field, $table, $key, $value ) {
		$text = "@@@{$table}@@{$field}@@{$key}@@{$value}";

		return $text;
	}

	/**
	 * @param $text
	 * @param array|string|int $params
	 *
	 * @return string
	 */
	public static function get( $text, $params = false, $layout = false ) {
		$translator = t::getInstance();

		return $translator->_get( $text, $params, $layout );
	}

	private function getHash($layout, $text){
		return 't_'.$this->language->id.'_'.$layout.'_'.md5($text);
	}

	private function _get( $text, $params = false, $layout = false ) {
		if ( ! $text ) {
			return '';
		}
		$text = trim( $text );

		$hash  = $this->getHash($layout, $text);
		$model = true;
		$value = Yii::app()->cache->get($hash);
		if (!$value){
			$model = $this->getLanguageModel( $layout )->findByAttributes( array(
				'lang_id' => $this->language->id,
				'slug'    => $text
			));
			$value = $model->value;
			Yii::app()->cache->set($hash, $value, 24*3600);
		}

		if ( !$model ) {
			$layout = $layout ? $layout : $this->languageModelSlug;
			switch ( $layout ) {
				case self::MODEL_GAME:
					$model = new LanguageTranslate();
					break;
				case self::MODEL_HOME:
					$model = new LanguageTranslateHome();
					break;
			}

			$model->slug    = $text;
			$model->value   = $this->getDefaultContent( $text );
			$model->lang_id = t::getInstance()->language->id;
			if ( ! $model->save() ) {
				echo( "\r\n\r\nError for slug: $model->slug \r\n\r\n" );
				echo( CHtml::errorSummary( $model ) );
				die();
			}

			$value = $model->value;
			Yii::app()->cache->set($hash, $value, 24*3600);
		}

		if ( $params !== false ) {
			if ( ! is_array( $params ) ) {
				$params = array( $params );
			}
			$value = vsprintf( $value, $params );
		}

		return $value;
	}

	public static function getRealSlug( $text ) {
		if ( mb_substr( $text, 0, 3 ) == '@@@' ) {
			$params = explode( '@@', str_replace( '@@@', '', $text ) );
			if ( count( $params ) == 4 ) {
				list( $table, $field, $key, $key_value ) = $params;

				$val    = '';
				$params = explode( '@@', str_replace( '@@@', '', $text ) );
				if ( count( $params ) == 4 ) {
					list( $table, $field, $key, $key_value ) = $params;
					$val = Yii::app()->getDb()->commandBuilder->createSqlCommand( "SELECT `$field` FROM `$table` WHERE `$key` = :key_value", array(
						':key_value' => $key_value
					) )->queryScalar();
				}

				return "<b>{$field}</b> для значения #<b>{$key_value}</b> из таблицы <b>{$table}</b>:<br><i>" . CHtml::encode( $val ) . "</i>";
			}
		}

		return htmlentities( $text, null, 'UTF-8' );
	}

	private function getDefaultContent( $text ) {
		if ( mb_substr( $text, 0, 3 ) == '@@@' ) {
			$params = explode( '@@', str_replace( '@@@', '', $text ) );
			if ( count( $params ) == 4 ) {
				list( $table, $field, $key, $key_value ) = $params;
				$val = Yii::app()->getDb()->commandBuilder->createSqlCommand( "SELECT `$field` FROM `$table` WHERE `$key` = :key_value", array(
					':key_value' => $key_value
				) )->queryScalar();

				return $val ? $val : false;
			}
		}

		return $text;
	}

	private function prefered_language( array $known_langs ) {
		if ( ! isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			return false;
		}

		$user_pref_langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );

		foreach ( $user_pref_langs as $idx => $lang ) {
			$lang = substr( $lang, 0, 2 );
			if ( in_array( $lang, $known_langs ) ) {
				return $lang;
				break;
			}
		}

		return false;
	}

	private function remember_language( $lang_id ) {
		if ( isset( Yii::app()->session ) ) {
			Yii::app()->session[ self::$__sesstion_slug ] = $lang_id;
		}

		if ( Yii::app() instanceof CConsoleApplication ) {
			return;
		} else {
			$cookie         = new CHttpCookie( 'user_language', $lang_id );
			$cookie->expire = time() + 3600 * 24 * 30;
			$cookie->domain = BASE_DOMAIN;
			Yii::app()->request->cookies->add( 'user_language', $cookie );
		}
	}

	public static function get_forum_link() {
		$link = '';
		if ( t::getInstance()->language->slug == 'ru' ) {
			$link = "http://forum.revival.online/";
		} else {
			$link = "http://en.forum.revival.online/";
		}

		return $link;
	}
}

if ( ! class_exists( 'Yii' ) ) {
	mb_internal_encoding( "UTF-8" );

	// Setting internal encoding to UTF-8.
	if ( ! ini_get( 'mbstring.internal_encoding' ) ) {
		@ini_set( "mbstring.internal_encoding", 'UTF-8' );
		mb_internal_encoding( 'UTF-8' );
	}

	// change the following paths if necessary
	$yii    = dirname( __FILE__ ) . '/../../framework/yii.php';
	$config = dirname( __FILE__ ) . '/../config/main.php';

	// remove the following lines when in production mode
	defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );
	// specify how many levels of call stack should be shown in each log message
	defined( 'YII_TRACE_LEVEL' ) or define( 'YII_TRACE_LEVEL', 3 );

	require_once( $yii );
	Yii::createWebApplication( $config );
}