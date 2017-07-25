<?php

if (!class_exists('after_listen_form_helper')) {
    /**
     * Class after_listen_form_helper
     */
    class after_listen_form_helper
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
                    $value = filter_var($value, FILTER_SANITIZE_STRING);
                    break;
                case 'email':
                    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                    break;
                default:
                    $value = filter_var($value, FILTER_SANITIZE_STRING);
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
                    $response = filter_var($value, FILTER_SANITIZE_STRING);
                    break;
                case 'email':
                    $response = filter_var($value, FILTER_VALIDATE_EMAIL);
                    break;
                default:
                    $response = filter_var($value, FILTER_SANITIZE_STRING);
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
    }
}
