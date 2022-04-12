<?php

namespace Web\InterChat\Util;

class View {

    public static function render(string $view) {
        require __DIR__ . '/../View/' . $view . '.php';
    }

    public static function redirect() {

    }
}