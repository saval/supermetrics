<?php

namespace Reports;

use PDO;
use Storage\AbstractStorage;

class DbReports
{
    
    protected $db;
    
    public function __construct(AbstractStorage $db)
    {
        $this->db = $db;
    }
    
    public function getAverageLengthsByMonth()
    {
        $sql = 'SELECT DATE_FORMAT(created_time, "%c") AS post_month, AVG(LENGTH(message)) AS average_length
                FROM post
                GROUP BY post_month';
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function getLongestPostsByMonth()
    {
        $sql = 'SELECT post_month, message FROM (
                    SELECT DATE_FORMAT(created_time, "%c") AS post_month, MAX(LENGTH(message)) AS max_length
                    FROM post GROUP BY post_month
                ) AS foo
                JOIN post p ON DATE_FORMAT(p.created_time, "%c") = foo.post_month AND LENGTH(p.message) = foo.max_length';
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function getPostsNumberByWeek()
    {
        $sql = 'SELECT COUNT(*) AS posts_num, DATE_FORMAT(created_time, "%v") AS post_week
                FROM post
                GROUP BY post_week
                ORDER BY post_week';
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function getAveragePostsPerUserPerMonth()
    {
        $sql = 'SELECT DATE_FORMAT(created_time, "%c") AS post_month, COUNT(*) / COUNT(DISTINCT from_id) AS average_posts_per_user
                FROM post
                GROUP BY post_month';
        $data = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}
