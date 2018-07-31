<?php declare(strict_types=1);

namespace FlexPhpIO;


class Response {
    /**
     * @var bool
     */
    private static $isBufferEnabled;

    /**
     * @throws \Exception
     */
    public static function bufferStart() {
        ob_end_clean();
        if (!ob_start()) {
            throw new \Exception("Unable to start output buffer. Please enable it in the PHP settings.");
        }

        self::$isBufferEnabled = true;
    }

    public static function bufferEndClean() {
        ob_end_clean();

        self::$isBufferEnabled = false;
    }

    /**
     * @param int $http_status_code
     */
    public static function JsonContentType($http_status_code = 200) {
        @header("Content-type: application/json; charset=utf-8", true, $http_status_code);
    }

    /**
     * @param int $http_status_code
     */
    public static function HtmlContentType($http_status_code = 200) {
        @header("Content-type: text/html; charset=utf-8", true, $http_status_code);
    }

    /**
     * @param int $http_status_code
     */
    public static function TextContentType($http_status_code = 200) {
        @header("Content-type: text/plain; charset=utf-8", true, $http_status_code);
    }

    /**
     * @return bool
     */
    public static function isBufferEnabled(): bool {
        return self::$isBufferEnabled;
    }

    /**
     * Call it once for the final output.
     * For bigger JSON: better don't use this method, but: disable the output buffering,
     *  set the content type to JSON, and output the parts of the JSON one by one
     *  using echo or print.
     *
     * @param array $json
     * @param int $http_status_code Ignored in CLI mode.
     */
    public static function printJson(array $json, $http_status_code = 200) {
        if (php_sapi_name() == "cli") {
            echo json_encode($json, JSON_PRETTY_PRINT)."\n\n";
        } else {
            self::JsonContentType($http_status_code);
            echo json_encode($json, JSON_PRETTY_PRINT);
        }
    }

    /**
     * @param string $path
     * @param int $http_status_code
     */
    public static function printHtmlFile(string $path, $http_status_code = 200) {
        $content = @file_get_contents($path);
        if (!is_string($content)) {
            throw new \Exception("File not found: $path");
        }

        self::HtmlContentType($http_status_code);
        echo $content;
    }
}
