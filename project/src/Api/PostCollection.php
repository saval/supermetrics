<?php

namespace Api;

use Storage\AbstractStorage;

class PostCollection
{
    protected $auth;
    protected $request;
    protected $db;
    protected $posts_url;

    public function __construct(Request $request, Auth $auth, AbstractStorage $db, string $url)
    {
        if (empty($url)) {
            throw new \Exception('Incorrect parameters');
        }
        
        $this->request = $request;
        $this->auth = $auth;
        $this->db = $db;
        $this->posts_url = $url;
    }
    
    /**
     * Method get all existing data from API end point and saves them into the storage
     * @throws \Exception
     */
    public function fetchAll(): void
    {
        $this->db->removeAll('post');
        $page_num = 1;
        $request_data = ['sl_token' => $this->auth->getToken(), 'page' => $page_num];
        while ($posts_json = $this->request->get($this->posts_url, $request_data)) {
            $parsed_data = json_decode($posts_json, true);
            if (is_null($parsed_data)) {
                throw new \Exception('JSON decoding error: ' . json_last_error_msg());
            }
            
            if (!empty($parsed_data['error'])) {
                if (0 === strcasecmp($parsed_data['error']['message'], 'Invalid SL Token')) {
                    $this->auth->registerToken();
                    $request_data['sl_token'] = $this->auth->getToken();
                    continue;
                } else {
                    throw new \Exception('API returns the error: ' . $parsed_data['error']['message']);
                }
            }
            
            if ($parsed_data['data']['page'] < $page_num) { //allowed max page number reached
                break;
            }

            $this->savePosts($parsed_data['data']['posts']);
            $page_num++;
            $request_data['page'] = $page_num;
        }
    }
    
    /**
     * @param array $post_ar
     */
    protected function savePosts(array $post_ar)
    {
        if (!$post_ar) {
            return;
        }
        foreach ($post_ar as $api_data) {
            $post = new Post($this->db, $api_data);
            $post->save();
        }
    }
}
