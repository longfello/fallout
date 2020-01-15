<?php

class ga {
    const GA_URL = 'http://www.google-analytics.com/collect?';

    private $data = array();

    public function __construct($UA = null, $domain = null) {
        $this->reset();

        $this->data['tid'] = $UA;
        $this->data['cid'] = substr(md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']), 0, 8);
        $this->data['uip'] = $_SERVER['REMOTE_ADDR'];
        $this->data['ua'] = urlencode($_SERVER['HTTP_USER_AGENT']);

        $this->data['dh'] =  isset( $domain ) ? $domain : $_SERVER['SERVER_NAME'];
    }

    public function set_event($category, $action, $label = false, $value = false) {
        $this->data['t'] = 'event';

        $this->data['ec'] = $category;
        $this->data['ea'] = $action;
        if ($label) $this->data['el'] = $label;
        if ($value) $this->data['ev'] = $value;
    }

    public function send() {
        $sGaUrl = self::GA_URL;

        foreach($this->data AS $sKey => $sValue) {
            if ($sValue) $sGaUrl.= "$sKey=$sValue&";
        }

        $sGaUrl = substr($sGaUrl, 0, -1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sGaUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_exec($ch);
        curl_close($ch);

        $this->reset();
    }

    public function reset() {
        $data = array(
            'v' => '1',
            'tid' => null,
            'aip' => '1',
            'ds' => 'web',
            'qt'  => 0,
            'cid' => null,
            'uip' => null,
            'ua' => null,
            't' => null,
            'dh' => null,
            'dp' => null,
            'dt' => null,

            //events
            'ec' => null,
            'ea' => null,
            'el' => null,
            'ev' => null,
        );
        return $this->data = $data;
    }

}