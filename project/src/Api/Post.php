<?php

namespace Api;

use Storage\AbstractStorage;

class Post
{
    protected $id;
    protected $from_name;
    protected $from_id;
    protected $message;
    protected $type;
    protected $created_time;
    protected $db;
    
    public function __construct(AbstractStorage $db, array $data)
    {
        $this->db = $db;
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function save(): void
    {
        $data = get_object_vars($this);
        unset($data['db']);
        $this->db->saveData('post', $data);
    }
}
