<?php

namespace App\Lib\Http;

class Request {
    private string $uri;
    private string $method;
    private array $headers;
    private ?array $body = null;

    public function __construct() {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getBody(): ?array {
        if ($this->body !== null) {
            return $this->body;
        }

        // Check if json
        $contentType = $this->getHeaders()["Content-Type"] ?? $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'];
        if (stripos($contentType, 'application/json') === false) {
            throw new \RuntimeException('Content-Type must be application/json');
        }

        // get json
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new \RuntimeException('Invalid JSON body');
        }

        $this->body = $data;
        return $this->body;
    }

}
