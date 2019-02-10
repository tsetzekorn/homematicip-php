<?php
spl_autoload_register(function ($class_name) {
    include_once(__DIR__ . '/classes/' . $class_name . '.php');
});
?> 