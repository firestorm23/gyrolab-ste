<?php

global $debug;
$debug = 0;


function fileDump($ar, $append, $check_var = null) {
    if ($append) {
        $append = FILE_APPEND;
    }

    if ($check_var === null || ($check_var && $check_var !== null)) {
        if (is_object($ar) || is_callable($ar) || is_resource($ar) || is_bool($ar) || is_null($ar)) {

            ob_start();
            var_dump($ar);
            $dump = ob_get_clean();

            file_put_contents(abs_path("/dump.html"), strip_tags($dump), $append);
        } else {
            file_put_contents(abs_path("/dump.html"), print_r($ar, true), $append);
        }
    }
}

function allow_debug() {
    global $debug;
    $debug = 1;
};

function disallow_debug() {
    global $debug;
    $debug = 0;
};

function fDump($ar) {
    global $debug;
    fileDump($ar, true, $debug);
}

function cDump($var) {
    if (!class_exists('PC')) {
        PhpConsole\Helper::register();
    }
    PC::debug($var);
    /*$dump_service = getDumpService();
    if (is_object($dump_service)) {
        $dump_service->log($var);
    }*/

}

function getDumpService() {
    global $kernel;
    $service_container = $kernel->getContainer();
    if (is_object($service_container)) {
        return $service_container->get('vitre_php_console');
    }
    return false;
}

function enable_browser_execute() {
    global $kernel;
    $dump_service = getDumpService();
    /** @var $connector \PhpConsole\Connector */
    $driver = $dump_service->getDriver();
    $connector = $driver->getConnection();
//
//// Configure eval provider
    $evalProvider = $connector->getEvalDispatcher()->getEvalProvider();
    $evalProvider->addSharedVar('post', $_POST); // so "return $post" code will return $_POST
    $evalProvider->setOpenBaseDirs(array($kernel->getRootDir()."/..")); // see http://php.net/open-basedir

    $connector->startEvalRequestsListener(); // must be called in the end of all configurations
}

function console_stack() {
    //функция выводит через плагин php-console стак функций вызывающий функцию, где мы объявляем вызов этой функции
    cDump(xdebug_get_function_stack());
}

function start_trace($clean_old = false) {
    if ($clean_old && is_file(abs_path("/xdebug.html"))) {
        unlink(abs_path("/xdebug.html"));
    }
    xdebug_start_trace(abs_path("/xdebug.html"));
    allow_debug();
}

function get_class_file($entry) {
    $class = new ReflectionClass($entry);
    return $class->getFileName();
}

function get_method_file($class_entry, $method_entry) {
    $method = new ReflectionMethod($class_entry, $method_entry);
    return $method->getFileName();
}

function get_class_definition($instance) {
    return array(
        get_class($instance),
        get_class_methods($instance),
        get_class_file($instance)
    );
}

function stop_trace() {
    xdebug_stop_trace();
    disallow_debug();
}

function abs_path($path) {
    if (is_string($path) && strlen($path) > 0) {
        global $kernel;
        return $kernel->getRootDir()."/../web" . $path;
    }
    return false;
}

function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
    -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

function traced (\Exception $e = null, $append = true) {
    if (!$e) {
        $e = new \Exception();
    }
    if ($append) {
        $append = FILE_APPEND;
    }
    file_put_contents($_SERVER['DOCUMENT_ROOT']."/trace.html", $e->getTraceAsString()."\n\n", $append);
}


function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}










?>