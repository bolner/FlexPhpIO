<?php declare(strict_types=1);

namespace FlexPhpIO;


class Request {
    /**
     * @return array
     * @throws \Exception
     */
    public static function getPostedJson(): array {
        $input = trim(file_get_contents("php://input"));
        if ($input == "") {
            throw new \Exception("Empty request body. A JSON string was expected.");
        }

        $json = @json_decode($input, true);

        if (!is_array($json)) {
            throw new \Exception("The posted JSON string has syntax error.");
        }

        return $json;
    }

    /**
     * Checks if an HTTP GET parameter is set.
     *
     * @param string $paramName
     * @return bool
     */
    public static function isParameterSet(string $paramName): bool {
        return isset($_GET[$paramName]);
    }

    /**
     * Converts an HTTP GET parameter to integer and returns it.
     *
     * @param string $paramName
     * @return int
     */
    public static function getIntParameter(string $paramName): int {
        return @(int)$_GET[$paramName];
    }

    /**
     * Converts an HTTP GET parameter to float and returns it.
     *
     * @param string $paramName
     * @return float
     */
    public static function getFloatParameter(string $paramName): float {
        return @(float)$_GET[$paramName];
    }

    /**
     * Converts an HTTP GET parameter to string and returns it.
     *
     * @param string $paramName
     * @return string
     */
    public static function getStringParameter(string $paramName): string {
        return @trim((string)$_GET[$paramName]);
    }

    /**
     * Converts an HTTP GET parameter to boolean and returns it.
     *
     * @param string $paramName
     * @return bool
     */
    public static function getBoolParameter(string $paramName): bool {
        return in_array(strtolower(@trim((string)$_GET[$paramName])), ['1', 'true', 'yes', 'enabled']);
    }

    /**
     * @return string
     */
    public static function getHttpRequestMethod(): string {
        return $_SERVER['REQUEST_METHOD'];
    }
}
