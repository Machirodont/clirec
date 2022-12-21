<?php declare(strict_types=1);
/**
 * CliRequest object represents command line parameters.
 *
 * Call script in CLI with parameters like
 * > php index.php -ab --key1=value1 value2
 *
 * $req=CliRequest::getRequest();
 * $req->get('a');      //true
 * $req->get('b');      //true
 * $req->get('ab');     //null
 * $req->get('key1');   //'value1'
 * $req->get(0);        //'value2'
 */
namespace Clirec;

use Exception;

final class CliRequest
{
    private static CliRequest $instance;
    private array $keys;

    /**
     * @param string|int $key
     * @return string|bool|null
     */
    public function get($key)
    {
        return array_key_exists($key, $this->keys) ? $this->keys[$key] : null;
    }

    public static function getRequest()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $parser = new CliRequestParser();
        if (!isset($_SERVER['argv'])) {
            throw new Exception("CliRequest can't be initialized in non-cli mode.");
        }
        $this->keys = $parser->parse($_SERVER['argv']);
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }
}