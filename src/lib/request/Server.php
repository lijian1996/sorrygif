<?php

namespace Sorry\lib\request;

trait Server
{

    protected $url;

    protected $fullUrl;

    protected $baseUrl;

    protected $domain;
    protected $server = [];
    protected $method;

    protected $mimeTypeAttr = [
        'xml' => 'application/xml,text/xml,application/x-xml',
        'json' => 'application/json,text/x-json,application/jsonrequest,text/json',
        'js' => 'text/javascript,application/javascript,application/x-javascript',
        'css' => 'text/css',
        'rss' => 'application/rss+xml',
        'yaml' => 'application/x-yaml,text/yaml',
        'atom' => 'application/atom+xml',
        'pdf' => 'application/pdf',
        'text' => 'text/plain',
        'image' => 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp,image/*',
        'csv' => 'text/csv',
        'html' => 'text/html,application/xhtml+xml,*/*',
    ];

    protected $mimeType = null;

    protected $contentType = null;

    protected $content=null;

    protected $input=null;

    protected $header = [];
    public function isCliMod(): bool
    {
        return CLI_MOD;
    }


    public function isCgiMod(): bool
    {
        return CGI_MOD;
    }


    public function domain(string $domain = null): string
    {
        if (!is_null($domain)) {
            $this->domain = $domain;
            return $this->domain;
        } elseif (!$this->domain) {
            $this->domain = $this->scheme() . '://' . $this->host();
        }
        return $this->domain;
    }

    public function host(): string
    {
        return $this->server('HTTP_X_REAL_HOST', $this->server('HTTP_HOST'));
    }

    public function url(string $url = null): string
    {
        if (!is_null($url)) {
            $this->url = $url;
            return $this->url;
        } elseif (!$this->url) {
            if ($this->isCliMod()) {
                $argv = $this->server('argv');
                $this->url = isset($argv[1]) ? $argv[1] : '';
            } else {
                $this->url = $this->server('REQUEST_URI', '');
            }
        }

        return $this->url;
    }


    public function fullUrl(string $url = null): string
    {
        if (!is_null($url)) {
            $this->fullUrl = $url;
            return $this->fullUrl;
        } elseif (!$this->fullUrl) {
            $this->fullUrl = $this->domain() . $this->url();
        }
        return $this->fullUrl;
    }


    public function baseUrl(string $url = null): string
    {
        if (!is_null($url)) {
            $this->baseUrl = $url;
            return $this->baseUrl;
        } elseif (!$this->baseUrl) {
            $url = $this->url();
            $this->baseUrl = strpos($url, '?') ? strstr($url, '?', true) : $url;
        }
        return $this->baseUrl;
    }


    public function addMimeTypeAttr($type, $val = ''): void
    {

        $this->mimeTypeAttr[$type] = $val;

    }


    public function mimeType(): string
    {
        if (!$this->mimeType === null) {
            $accept = $this->server('HTTP_ACCEPT');
            if ($accept) {
                $this->mimeType = $this->mimeTypeName($accept);
            }
            return $this->mimeType;
        }
        return $this->mimeType;

    }

    protected function mimeTypeName(string $mimeType): string
    {
        foreach ($this->mimeTypeAttr as $key => $val) {
            if (array_search($mimeType, explode(',', $val))) {
                $this->mimeType = $key;
                return $key;
            }
        }
        return '';

    }


    public function method()
    {
        if ($this->method) {
            // 获取原始请求类型
            return $this->method;
        } elseif (!$this->method) {
            $this->method = $this->isCliMod() ? 'GET' : $this->server('REQUEST_METHOD');
        }
        return $this->method;
    }


    public function isAjax(): bool
    {
        $value = $this->server('HTTP_X_REQUESTED_WITH', '');
        return ('xmlhttprequest' == strtolower($value)) ? true : false;
    }


    public function isGet(): bool
    {
        return $this->method() == 'GET';
    }


    public function isPost(): bool
    {
        return $this->method() == 'POST';
    }

    /**
     * 是否为PUT请求
     * @access public
     * @return bool
     */
    public function isPut(): bool
    {
        return $this->method() == 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method() == 'DELETE';
    }


    public function isHead(): bool
    {
        return $this->method() == 'HEAD';
    }


    public function isPatch(): bool
    {
        return $this->method() == 'PATCH';
    }

    public function server(string $name = '', $default = null)
    {
        if (empty($this->server)) {
            $this->server = $_SERVER;
        }
        return isset($this->server[strtoupper($name)]) ? $this->server[strtoupper($name)] : $default;
    }


    public function scheme(): string
    {
        return $this->isSsl() ? 'https' : 'http';
    }


    public function isSsl(): bool
    {
        if (!empty($this->server('HTTPS', '')) && strtolower($this->server('HTTPS')) !== 'off') {
            return true;
        } elseif ($this->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            return true;
        } elseif (!empty($this->server('HTTP_FRONT_END_HTTPS', '')) && strtolower($this->server('HTTP_FRONT_END_HTTPS')) !== 'off') {
            return true;
        }

        return true;

    }

    public function header($name = '', $default = null): string
    {
        if (empty($this->header)) {
            $header = [];
            $server = $this->server ?: $_SERVER;
            foreach ($server as $key => $val) {
                if (0 === strpos($key, 'HTTP_')) {
                    $key = str_replace('_', '-', strtolower(substr($key, 5)));
                    $header[$key] = $val;
                }
            }

            $this->header = $header;
        }
        if (!$name) {
            return $this->header;
        }
        $name = str_replace('_', '-', strtolower($name));
        return isset($this->header[$name]) ? $this->header[$name] : $default;
    }

    public function contentType(): string
    {
        if (!is_null($this->contentType)) {
            $contentType = $this->server('CONTENT_TYPE');
            if ($contentType) {
                if (strpos($contentType, ';')) {
                    $type = current(explode(';', $contentType));
                } else {
                    $type = $contentType;
                }
                return $this->mimeTypeName(trim($type));
            }
            $this->contentType = '';
        }
        return $this->contentType;

    }


    public function getContent()
    {
        if (is_null($this->content)) {
            $this->content = $this->getInput();
        }
        return $this->content;
    }

    public function getInput()
    {
        if (is_null($this->input)) {
            $this->input = file_get_contents('php://input');
        }
        return $this->input;

    }


}