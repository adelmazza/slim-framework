<?php

namespace App\Objects;


final class Session
{

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    public function __unset($key)
    {
        $this->delete($key);
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public function isActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function regenerateId()
    {
        if ($this->isActive()) {
            session_regenerate_id(true);
        }
    }

    public function delete($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }

    public function clearAll()
    {
        $_SESSION = [];
    }

    public function destroy()
    {
        if ($this->isActive()) {
            $_SESSION = [];
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );

            session_destroy();
        }
    }

}