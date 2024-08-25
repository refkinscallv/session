<?php

    namespace RF\Session;

    class Session {

        private $timeOut;

        public function __construct(array $args = []) {
            $this->timeOut = 3600;

            if(is_array($args) && !empty($args)) {

                if(isset($args["timeout"])) {
                    $this->timeOut = $args["timeout"];
                }

            }

            $this->checkTimeout();
        }

        private function checkTimeout() {
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $this->timeOut) {
                $this->destroy();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }

        public function start(): void {
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function set($key, $value = null): void {
            $this->start();

            if(is_array($key)) {
                foreach ($key as $idx => $val) {
                    $_SESSION[$idx] = $val;
                }
                return;
            }

            $_SESSION[$key] = $value;
        }

        public function get(string $key, $default = null) {
            $this->start();

            return $_SESSION[$key] ?? $default;
        }

        public function some(array $keys, $default = null): array {
            $this->start();

            if(is_array($keys)) {
                $values = [];

                foreach ($keys as $key) {
                    $values[$key] = $_SESSION[$key] ?? $default;
                }

                return $values;
            }

            return [];
        }

        public function has(string $key): bool {
            $this->start();

            return isset($_SESSION[$key]);
        }

        public function remove(string $key): void {
            $this->start();

            unset($_SESSION[$key]);
        }

        public function setFlash(string $key, $value): void {
            $this->start();

            $_SESSION['flash_data'][$key] = $value;
        }

        public function getFlash(string $key, $default = null) {
            $this->start();

            $value = $_SESSION['flash_data'][$key] ?? $default;

            $this->removeFlash($key);

            return $value;
        }

        private function removeFlash(string $key): void {
            unset($_SESSION['flash_data'][$key]);
        }

        public function clear(): void {
            $this->start();

            session_unset();
        }

        public function destroy(): void {
            $this->start();

            session_destroy();
        }

    }
