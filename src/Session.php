<?php

    namespace RF\Session;

    /**
     * Class Session
     * Handles session management with additional features like timeout and flash data.
     */
    class Session
    {
        
        protected int $sessionTimeout;
        protected string $sessionDomain;
        protected string $sessionPath;
        protected bool $sessionSecure;
        protected string $sessionName;

        /**
         * Session constructor.
         * Initializes session configurations and checks for timeout.
         */
        public function __construct()
        {
            $this->sessionTimeout = $this->getServerValue("SESSION_TIMEOUT", 1) * 3600;
            $this->sessionDomain = $_SERVER["SERVER_NAME"] ?? '';
            $this->sessionPath = $this->getServerValue("SESSION_PATH", '/');
            $this->sessionSecure = (bool)$this->getServerValue("SESSION_SECURE", false);
            $this->sessionName = $this->getServerValue("SESSION_NAME", 'web_session');

            $this->checkTimeout();
        }

        /**
         * Retrieve a value from the server environment.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        private function getServerValue(string $key, mixed $default): mixed
        {
            return $_SERVER[$key] ?? $default;
        }

        /**
         * Checks if the session has timed out and destroys it if necessary.
         */
        private function checkTimeout(): void
        {
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $this->sessionTimeout) {
                $this->destroy();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }

        /**
         * Starts the session if it hasn't already started.
         */
        public function start(): void
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_set_cookie_params([
                    'lifetime' => $this->sessionTimeout,
                    'path' => $this->sessionPath,
                    'domain' => $this->sessionDomain,
                    'secure' => $this->sessionSecure,
                    'httponly' => !$this->sessionSecure,
                    'samesite' => 'Strict',
                ]);
                session_name($this->sessionName);
                session_start();
            }
        }

        /**
         * Sets a session value or multiple values if an associative array is provided.
         *
         * @param string|array $key
         * @param mixed|null $value
         */
        public function set(string|array $key, mixed $value = null): void
        {
            $this->start();

            if (is_array($key)) {
                foreach ($key as $idx => $val) {
                    $this->validateKey($idx);
                    $_SESSION[$idx] = $val;
                }
            } else {
                $this->validateKey($key);
                $_SESSION[$key] = $value;
            }
        }

        /**
         * Retrieves a session value by key.
         *
         * @param string $key
         * @param mixed|null $default
         * @return mixed
         */
        public function get(string $key, mixed $default = null): mixed
        {
            $this->start();
            $this->validateKey($key);

            return $_SESSION[$key] ?? $default;
        }

        /**
         * Retrieves multiple session values by their keys.
         *
         * @param array $keys
         * @param mixed|null $default
         * @return array
         */
        public function some(array $keys, mixed $default = null): array
        {
            $this->start();
            $values = [];

            foreach ($keys as $key) {
                $this->validateKey($key);
                $values[$key] = $_SESSION[$key] ?? $default;
            }

            return $values;
        }

        /**
         * Checks if a session key exists.
         *
         * @param string $key
         * @return bool
         */
        public function has(string $key): bool
        {
            $this->start();
            $this->validateKey($key);

            return isset($_SESSION[$key]);
        }

        /**
         * Removes a session value by key.
         *
         * @param string $key
         */
        public function remove(string $key): void
        {
            $this->start();
            $this->validateKey($key);

            unset($_SESSION[$key]);
        }

        /**
         * Sets a flash session value.
         *
         * @param string $key
         * @param mixed $value
         */
        public function setFlash(string $key, mixed $value): void
        {
            $this->start();
            $this->validateKey($key);

            $_SESSION['flash_data'][$key] = $value;
        }

        /**
         * Retrieves and removes a flash session value.
         *
         * @param string $key
         * @param mixed|null $default
         * @return mixed
         */
        public function getFlash(string $key, mixed $default = null): mixed
        {
            $this->start();
            $this->validateKey($key);

            $value = $_SESSION['flash_data'][$key] ?? $default;
            $this->removeFlash($key);

            return $value;
        }

        /**
         * Removes a flash session value by key.
         *
         * @param string $key
         */
        private function removeFlash(string $key): void
        {
            unset($_SESSION['flash_data'][$key]);
        }

        /**
         * Clears all session values.
         */
        public function clear(): void
        {
            $this->start();
            session_unset();
        }

        /**
         * Destroys the session.
         */
        public function destroy(): void
        {
            $this->start();
            session_destroy();
        }

        /**
         * Validates the session key.
         *
         * @param string $key
         */
        private function validateKey(string $key): void
        {
            if (empty($key)) {
                throw new \InvalidArgumentException("Session key must be a non-empty string.");
            }
        }

    }
