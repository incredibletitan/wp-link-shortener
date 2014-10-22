<?php
/**
 * @author Pretender
 */

class GoogleUrlApi
{
    private $ch;
    private $apiURL;

    /**
     * @param string $key google api key
     * @param string $apiURL url to google shortener
     */
    function __construct($key, $apiURL = 'https://www.googleapis.com/urlshortener/v1/url')
    {
        $this->apiURL = $apiURL . '?key=' . $key;
        $this->ch =curl_init();

        //setting SSL ignoring
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    /**
     * @param string $url long url
     * @return bool or string short url
     */
    function shorten($url)
    {
        $response = $this->send($url);

        return isset($response['id']) ? $response['id'] : false;
    }

    /**
     * @param string $url
     * @return bool or string short url
     */
    function expand($url)
    {
        $response = $this->send($url, false);

        return isset($response['longUrl']) ? $response['longUrl'] : false;
    }

    /**
     * @param string $proxy proxy like 'username:pass@address:port' or 'address:port'
     */
    function setProxy($proxy) {

        if (!empty($proxy)) {
            curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
        }
    }

    /**
     * @param string $url
     * @param bool $shorten is it shorten url or not
     * @return string json result
     */
    function send($url, $shorten = true)
    {

        if ($shorten) {
            curl_setopt($this->ch, CURLOPT_URL, $this->apiURL);
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $url)));
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

        } else {
            curl_setopt($this->ch, CURLOPT_URL, $this->apiURL . '&shortUrl=' . $url);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($this->ch);
        curl_close($this->ch);

        return json_decode($result, true);
    }
}