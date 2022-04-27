<?php

namespace Web\InterChat\Util;

class View {

    public static function render(string $view, array $model) {
        require __DIR__ . '/../View/' . $view . '.php';
    }

    public static function redirect(string $path) {
        header("Location: $path");
        if(getenv('type') != 'test') {
            exit();
        }
    }
}