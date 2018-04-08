<?php

namespace Sorry\lib\request;
trait Filter
{
    protected $filter;
    public function input( $data = [], $name = '', $default = null, $filter = '')
    {

        $name = (string)$name;

        if ('' != $name) {
            // 解析name
            if (strpos($name, '/')) {
                list($name, $type) = explode('/', $name);
            } else {
                $type = 'str';
            }
            foreach (explode('.', $name) as $val) {
                if (isset($data[$val])) {
                    $data = $data[$val];
                } else {
                    return $default;
                }
            }
            if (is_object($data)) {
                return $data;
            }
        }

        $filter = $this->getFilter($filter);

        if (is_array($data)) {
            array_walk_recursive($data, function (&$value, $key) use ($filter, $default) {
                return $this->filterValue($value, $filter, $default);
            });
            reset($data);
        } else {
            $this->filterValue($data, $filter, $default);
        }

        if (isset($type) && $data !== $default) {
            $this->typeCast($data, $type);
        }
        return $data;
    }

    public function filter($filter = null)
    {
        if (is_null($filter)) {
            return $this->filter;
        } else {
            $this->filter = $filter;
        }
    }

    protected function getFilter($filter)
    {
        if (!is_null($filter)) {
            $filter = $filter ?: $this->filter;
            if (is_string($filter) && false === strpos($filter, '/')) {
                $filter = explode(',', $filter);
            } else {
                $filter = (array)$filter;
            }
        }


        return $filter;
    }


    private function filterValue(&$value, $filters, $default = null)
    {

        $filterValue = function (&$value, $filter, $type) {
            switch ($type) {
                case 'callback':
                    return call_user_func($filter, $value);
                    break;
                case 'pcre':
                    return preg_match($filter, $value);
                    break;
                case 'filter':
                    return filter_var($value, is_int($filter) ? $filter : filter_id($filter));
                    break;
                default:
                    return $value;
                    break;
            }
        };

        foreach ($filters as $filter) {
            $result = true;
            if (is_callable($filter)) {
                $result = $filterValue($value, $filter, 'callback');
            } elseif (is_scalar($value)) {
                if (false !== strpos($filter, '/')) {
                    $result = $filterValue($value, $filter, 'pcre');
                } elseif (!empty($filter)) {
                    $result = $filterValue($value, $filter, 'filter');
                }
            }
            if (false === $result) {
                $value = $default;
            }
        }
        return $value;
    }




    private function typeCast(&$data, string $type)
    {
        switch (strtolower($type)) {
            case 'array':
                $data = (array)$data;
                break;
            case 'int':
                $data = (int)$data;
                break;
            case 'float':
                $data = (float)$data;
                break;
            case 'bool':
                $data = (boolean)$data;
                break;
            case 'str':
            default:
                if (is_scalar($data)) {
                    $data = (string)$data;
                } else {
                    throw new \InvalidArgumentException('variable type error：' . gettype($data));
                }
        }
    }

}