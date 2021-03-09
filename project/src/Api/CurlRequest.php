<?php

namespace Api;

class CurlRequest implements Request
{
    protected $options = [];
    
    public function __construct()
    {
        $this->options = [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 4
        ];
    }
    
    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    public function post(string $url, array $data): string
    {
        $post_options = [
            CURLOPT_POST => 1,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ];
        return $this->call($post_options);
    }
    
    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    public function get(string $url, array $data): string
    {
        $get_options = [
            CURLOPT_URL => $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($data),
        ];
        return $this->call($get_options);
    }
    
    /**
     * @param array $options
     * @return string
     */
    protected function call(array $options): string
    {
        $ch = curl_init();
        $all_options = $this->options + $options;
        curl_setopt_array($ch, $all_options);
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
