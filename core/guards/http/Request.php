<?php

namespace Horizon\Core\Guards\Http;

class Request {
    protected $query;
    protected $request;
    protected $server;
    protected $files;
    protected $cookies;
    protected $headers;

    public function __construct() {
        $this->query = $this->sanitize($_GET);
        $this->request = $this->sanitize($_POST);
        $this->server = $_SERVER;
        $this->files = $this->sanitizeFiles($_FILES);
        $this->cookies = $this->sanitize($_COOKIE);
        $this->headers = $this->sanitizeHeaders(getallheaders());
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    protected function sanitizeHeaders($headers) {
        foreach ($headers as $key => $value) {
            $headers[$key] = filter_var(trim($value), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        return $headers;
    }

    protected function sanitizeFiles($files) {
        foreach ($files as $key => $file) {
            $files[$key]['name'] = filter_var($file['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        return $files;
    }

    public function all() {
        return array_merge($this->query, $this->request, $this->files, $this->cookies);
    }

    public function input($key = null, $default = null) {
        if ($key === null) {
            return $this->request;
        }

        return $this->request[$key] ?? $this->query[$key] ?? $default;
    }

    public function query($key = null, $default = null) {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }

    public function has($key) {
        return isset($this->request[$key]) || isset($this->query[$key]);
    }

    public function file($key) {
        return $this->files[$key] ?? null;
    }

    public function server($key = null, $default = null) {
        if ($key === null) {
            return $this->server;
        }

        return $this->server[$key] ?? $default;
    }

    public function header($key = null, $default = null) {
        if ($key === null) {
            return $this->headers;
        }

        return $this->headers[$key] ?? $default;
    }

    public function cookie($key = null, $default = null) {
        if ($key === null) {
            return $this->cookies;
        }

        return $this->cookies[$key] ?? $default;
    }

    public function method() {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    public function isMethod($method) {
        return strtoupper($this->method()) === strtoupper($method);
    }

    public function getField($key, $default = null) {
        return $this->input($key, $default);
    }

    public function sanitizeString($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public function sanitizeEmail($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

    public function sanitizeInt($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public function validateInt($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    public function validateEmail($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function validateUrl($key, $default = null) {
        $value = $this->getField($key, $default);
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    public static function getFields() {
        $request = new self();
        $fields = [];
        foreach ($request->all() as $key => $value) {
            $fields[$key] = $request->sanitizeField($key, $value);
        }
        return $fields;
    }

    protected function sanitizeField($key, $value) {
        if (is_string($value)) {
            return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return filter_var($value, FILTER_SANITIZE_EMAIL);
        }
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        }
        return $value;
    }
}
