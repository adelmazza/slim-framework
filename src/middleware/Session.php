<?php

namespace App\Middleware;

use Tracy\Debugger;
use RuntimeException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Session
{

    protected $settings = [
        // Session cookie settings
        'name'           => 'adm_session',
        'lifetime'       => 24,
        'path'           => '/',
        'domain'         => null,
        'secure'         => false,
        'httponly'       => true,

        // Path where session files are stored, PHP's default path will be used if set null
        'save_path'      => null,
        // Session cache limiter
        'cache_limiter'  => 'nocache',
        // Extend session lifetime after each user activity
        'autorefresh'    => false,
    ];

    public function __construct(array $settings = [])
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $this->start($request);

        $response = $next($request, $response);

        if(Debugger::isEnabled())
            Debugger::barDump($_SESSION, 'Session');

        return $response;
    }

    protected function start(Request $request)
    {
        $settings = $this->settings;

        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);

        if (is_string($settings['lifetime'])) {
            // if lifetime is string, convert it to seconds
            $settings['lifetime'] = strtotime($settings['lifetime']) - time();
        } else {
            // if lifetime is minutes, convert it to seconds
            $settings['lifetime'] *= 60;
        }

        // Set number of seconds after which data will be seen as garbage
        if ($settings['lifetime'] > 0) {
            ini_set('session.gc_maxlifetime', $settings['lifetime']);
        }

        // Set path where session cookies are saved
        if (is_string($settings['save_path'])) {
            if (!is_writable($settings['save_path'])) {
                throw new RuntimeException('Session save path is not writable.');
            }
            ini_set('session.save_path', $settings['save_path']);
        }

        // Set session id strength
        if (version_compare(PHP_VERSION, '7.1', '<')) {
            // PHP version < 7.1
            ini_set('session.entropy_file', '/dev/urandom');
            ini_set('session.entropy_length', 128);
            ini_set('session.hash_function', 'sha512');
        } else {
            // PHP version >= 7.1
            ini_set('session.sid_length', 128);
        }
        // Set session cache limiter
        session_cache_limiter($settings['cache_limiter']);
        // Set session cookie name
        session_name($settings['name']);
        // Set session cookie parameters
        session_set_cookie_params(
            $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );

        // session start
        session_start();

        // Extend session lifetime
        if ($settings['autorefresh'] === true && isset($_COOKIE[$settings['name']])) {
            setcookie(
                $settings['name'],
                $_COOKIE[$settings['name']],
                time() + $settings['lifetime'],
                $settings['path'],
                $settings['domain'],
                $settings['secure'],
                $settings['httponly']
            );
        }
    }
}