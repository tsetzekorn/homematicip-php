<?php
spl_autoload_register(function ($class_name) {
    try{
        include_once(__DIR__ . '/classes/' . $class_name . '.php');
    } catch($e) {
        // Include does not exist
    }
});
?> 