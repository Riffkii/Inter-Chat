<?php

namespace Web\InterChat\Util {
    function header(string $header) {
        echo $header;
    }
}

namespace Web\InterChat\Middleware {
    function header(string $header) {
        echo $header;
    }
}

namespace Web\InterChat\Service {
    function setcookie($key, $value, $time, $path) {
        echo $key;
    }
}