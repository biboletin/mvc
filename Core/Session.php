<?php

/**
 * Session handler class
 * for more secure session variables
 *
 * USE
 * Session::start();
 * Session::set(key, value);
 * Session::get(key);
 * Session::del(key);
 * Session::close();
 *
 * PHP Version 7.2
 *
 * @category Session_Handler
 *
 * @package  Sessions
 *
 * @author   Biboletin <biboletin87@gmail.com>
 *
 * @license  MIT https://opensource.org/licenses/MIT
 *
 * @link     https://github.com/biboletin/php
 */

namespace Core;

/**
 * Session handler class
 * for more secure session variables
 *
 * @category Session_Handler
 *
 * @package  Sessions
 *
 * @author   Biboletin <biboletin87@gmail.com>
 *
 * @license  MIT https://opensource.org/licenses/MIT
 *
 * @link     https://github.com/biboletin/php
 */
final class Session
{
    /**
     * Session constructor.
     *
     * Set up private properties
     * Instantiate session
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(
                (int) Config::get('session.max_life_time'),
                Config::get('session.path'),
                Config::get('session.domain'),
                (bool) Config::get('session.secure'),
                (bool) Config::get('session.cookie_httponly')
            );
            session_start([
                'save_path' => Config::get('session.save_path'),
                'sid_length' => Config::get('session.sid_length'),
                'trans_sid_hosts' => Config::get('session.trans_sid_hosts'),
            ]);
        }
        $_SESSION = array_map('trim', $_SESSION);
        $_SESSION = array_map('strip_tags', $_SESSION);
    }

    public function start(): object
    {
        return new self();
    }

    /**
     * Adding session element
     *
     * @param mixed $sessionValue
     */
    public function set(string $sessionKey, $sessionValue): string
    {
        if (($sessionKey === null) || ($sessionKey === '')) {
            die('No session key!');
        }
        $key = strip_tags(trim(addslashes($sessionKey)));
        $value = strip_tags(trim(addslashes($sessionValue)));
        return $_SESSION[$key] = $value;
    }

    /**
     * Get session element by key
     */
    public function get(string $sessionKey): string
    {
        $key = strip_tags(trim(stripslashes($sessionKey)));
        return $_SESSION[$key] ?? '';
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session element
     */
    public function del(string $sessionKey): void
    {
        $key = strip_tags(trim($sessionKey));
        unset($_SESSION[$key]);
    }

    public function dump()
    {
        return var_dump($_SESSION);
    }

    public function name()
    {
        return session_name();
    }

    /**
     * Destroy session
     */
    public function close(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(), '', 0, '/');
        unset($_SESSION);
    }
}
