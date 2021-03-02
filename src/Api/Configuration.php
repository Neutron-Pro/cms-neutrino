<?php
namespace Neutrino\Api;

class Configuration
{
    private array $json;
    private string $path;

    public function __construct($path)
    {
        $this->path = $path;
        $this->json = json_decode(file_get_contents($path), true);
    }

    /**
     * @param string $key
     * @param ?mixed $def
     * @return ?mixed
     */
    public function get(string $key, $def = null)
    {
        $keys = explode('.', $key);
        $obj = $this->json;
        for ($i = 0; $i < count($keys)-1; $i++) {
            if(!isset($obj[$keys[$i]]) || !is_array($obj[$keys[$i]])) {
                return $def;
            }
            $obj = $obj[$keys[$i]];
        }
        return $obj[$keys[count($keys)-1]] ?? $def;
    }

    public function set(string $key, $value): self
    {
        $keys = explode('.', $key);
        $obj = [ $keys[count($keys)-1] => $value ];
        for($i = count($keys)-2; $i > -1; $i--)
        {
            $obj = [ $keys[$i] => $obj ];
        }
        $json = array_replace_recursive($this->json, $obj);
        if ($json !== null) {
            $this->json = $json;
        }
        return $this;
    }

    public function save(string $path = ''): void
    {
        $json = json_encode($this->json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $fp = fopen($path ?: $this->path, 'w');
        fwrite($fp, $json);
        fclose($fp);
    }

    public function forEach($callback): void
    {
        foreach ($this->json as $key => $value) {
            $callback($key, $value);
        }
    }
}
