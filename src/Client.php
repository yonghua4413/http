<?php

namespace Http;

class Client
{
    private $handle;

    public $default_config = [
        'conn_time_out' => 4000,
        'time_out' => 8000,
        'ssl' => false
    ];
    public $default_option = [
        'agent' => CURLOPT_USERAGENT,
        'referer' => CURLOPT_REFERER,
        'cookie' => CURLOPT_COOKIE
    ];

    public function __construct()
    {
        $this->handle = curl_init();
    }

    /**
     * @param string $requestUrl
     * @param string $method
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public function request(string $requestUrl, string $method, array $data, ?array $header)
    {
        $handle = $this->handle;
        curl_setopt($handle, CURLOPT_URL, $requestUrl);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT_MS, $this->default_config['conn_time_out']);
        curl_setopt($handle, CURLOPT_TIMEOUT_MS, $this->default_config['time_out']);
        if ($header) {
            $options_list = $this->getHeaderOptions($header);
            if ($options_list) {
                curl_setopt_array($handle, $options_list);
            }
        }
        if (strtolower($method) == 'post') {
            curl_setopt($handle, CURLOPT_POST, 1);
            if (count($data)) {
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            }
        }
        if (!$this->default_config['ssl']) {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);
        curl_close($handle);
        return $response;
    }

    private function getHeaderOptions(array $headers): array
    {
        $options = [];
        $options_key = array_keys($this->default_option);
        foreach ($headers as $key => $header) {
            if (in_array($key, $options_key)) {
                $options[$key] = $header;
            }
        }
        $available_options = [];
        if ($options) {
            foreach ($options as $k => $val) {
                foreach ($this->default_option as $key => $value) {
                    if ($k == $key) {
                        $available_options[$value] = $val;
                        break;
                    }
                }
            }
        }
        return $available_options;
    }
}
