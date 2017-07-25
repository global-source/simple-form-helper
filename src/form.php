<?php
    /**
     * Class form library
     */
    class form
    {
         /**
         * @var
         */
        protected $form;

        /**
         * @var bool
         */
        protected $toJSON = false;

        /**
         * @param $form
         * @return bool
         */
        public function setForm($form)
        {
            if (!is_array($form) || empty($form) || is_null($form)) return false;
            $this->form = $form;
        }

        /**
         * @param $toJSON
         */
        public function toJSON($toJSON)
        {
            $toJSON = boolval($toJSON);
            if (is_bool($toJSON)) {
                $this->toJSON = $toJSON;
            }
        }

        /**
         * @return bool
         */
        public function isToJSON()
        {
            return (bool)$this->toJSON;
        }

        /**
         * @param $key
         * @param bool $default
         * @return array|bool|mixed|string
         */
        public function get($key, $default = false)
        {
            $response = false;
            if (!isset($key) || is_null($key) || false === $key) $response = false;
            // Common form object.
            $form = $this->form;
            if (!is_array($form)) $response = false;
            // Existing check.
            if (isset($form[$key])) {

                $response = $form[$key];

                if (!is_array($response)) {
                    // Simple sanity check.
                    if (false === self::sanityCheck($response)) {
                        $response = false;
                    } else {

                        self::sanityFilter($response);
                    }
                } else {

                    if ($this->isToJSON()) $response = json_encode($response);
                }
            }
            return (false !== $response) ? $response : $default;
        }

        /**
         * @param $value
         * @param string $type
         */
        public static function sanityFilter(&$value, $type = 'string')
        {
            switch ($type) {
                case 'string':
                    $value = self::toString($value);
                    break;
                case 'email':
                    $value = self::toMail($value);
                    break;
                default:
                    $value = self::toString($value);
                    break;
            }
        }

        /**
         * @param $value
         * @param string $type
         * @return mixed
         */
        public static function sanityCheck($value, $type = 'string')
        {
            switch ($type) {
                case 'string':
                    $response = self::isString($value);
                    break;
                case 'email':
                    $response = self::isMail($value);
                    break;
                default:
                    $response = self::isString($value);
                    break;
            }
            return $response;
        }

        /**
         * To reset form completely.
         */
        public function reset()
        {
            $this->form = false;
        }

        /**
         * To convert into String.
         *
         * @param $string
         * @return bool|mixed
         */
        public static function toString($string)
        {
            // Convert string value.
            $string = strval($string);
            // If not valid, then return false.
            if (!$string) return false;
            // Return with sanitized string.
            return filter_var($string, FILTER_SANITIZE_STRING);
        }

        /**
         * To return, this is string or not.
         *
         * @param $email
         * @return bool
         */
        public static function isString($email)
        {
            // Convert into string value.
            $email = strval($email);
            // Return false, if not valid.
            if (!$email) return false;
            // Return true, if valid.
            return true;
        }

        /**
         * Convert into mail.
         *
         * @param $email
         * @return bool|mixed
         */
        public static function toMail($email)
        {
            // Convert to string.
            $email = strval($email);
            // If not a valid string, then return false.
            if (!$email) return false;
            // Return with valid email format.
            return filter_var($email, FILTER_SANITIZE_EMAIL);
        }

        /**
         * To check this is mail or not.
         *
         * @param $email
         * @return bool|mixed
         */
        public static function isMail($email)
        {
            // Convert into string.
            $email = strval($email);
            // If not a valid string, then return false.
            if (!$email) return false;
            // Return with validated email.
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    }
