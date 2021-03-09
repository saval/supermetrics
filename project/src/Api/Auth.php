<?php

namespace Api;

class Auth
{
    protected $request;
    protected $auth_url = '';
    protected $client_id = '';
    protected $email = '';
    protected $name = '';
    protected $token = '';
    
    public function __construct(Request $request, array $data)
    {
        if (empty($data['client_id']) || empty($data['email']) || empty($data['name']) || empty($data['url'])) {
            throw new \Exception('Incorrect parameters');
        }
        $this->request = $request;
        $this->client_id = $data['client_id'];
        $this->email = $data['email'];
        $this->name = $data['name'];
        $this->auth_url = $data['url'];
    }
    
    public function registerToken()
    {
        $post_data = [
            'client_id' => $this->client_id,
            'email' => $this->email,
            'name' => $this->name
        ];
        $json = $this->request->post($this->auth_url, $post_data);
        $parsed_data = json_decode($json, true);
        if (is_null($parsed_data)) {
            throw new \Exception('JSON decoding error: '.json_last_error_msg());
        }
        if (empty($parsed_data['data']['sl_token'])) {
            throw new \Exception('Incorrect API response: token missing');
        }
        $this->token = $parsed_data['data']['sl_token'];
    }
    
    public function getToken()
    {
        return $this->token;
    }
}
