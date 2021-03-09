<?php

namespace Api;

interface Request
{
    public function post(string $url, array $data): string;
    public function get(string $url, array $data): string;
}
