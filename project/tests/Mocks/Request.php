<?php

namespace Mocks;

class Request implements \Api\Request
{
    protected $data;
    
    public function __construct()
    {
        return;
    }
    
    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    public function post(string $url, array $data): string
    {
        return !empty($this->data) ? json_encode($this->data) : '';
    }
    
    /**
     * @param string $url
     * @param array $data
     * @return string
     */
    public function get(string $url, array $data): string
    {
        return !empty($this->data) ? json_encode($this->data) : '';
    }
    
    public function setDataToReturn(array $data): void
    {
        $this->data = $data;
    }
}
