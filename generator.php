<?php
include __DIR__ . '/vendor/autoload.php';

$content = file_get_contents('postman.json');
$data = json_decode($content, true);
$class = array();
$methods = array();
$params = array();
foreach ($data['item'] as $item) {
    $request = $item['request'];
    $temp_class = '';
    $temp_method = '';
    $req_method = '';
    $final_method = '';
    $final_param = '';

    //echo $item['name'] . '<br>';
    //logic to get all classes
    $temp_class = $request['url']['path'][0];
    if (!in_array($temp_class, $class)) {
        array_push($class, $temp_class);
    }
    $req_method = strtolower($request['method']);
    if (!isset($methods[$temp_class])) {
        $methods[$temp_class] = array();
    }

    if (isset($request['url']['path'][1])) {
        $temp_method = $request['url']['path'][1];
        if (strpos($temp_method, ':') === false) {
            $final_method = $req_method . ucfirst($temp_method);
            $final_param = 'none';
        } else {
            $final_method = $req_method . 'Index';
            $final_param = str_replace(':', '', $temp_method);
        }
    } else {
        $final_method = $req_method . 'Index';
        $final_param = 'none';
    }
    array_push($methods[$temp_class], $final_method);

    if (!isset($params[$temp_class][$final_method])) {
        $params[$temp_class][$final_method] = array();
    }
    array_push($params[$temp_class][$final_method], $final_param);
    foreach ($item['response'] as $response) {
        if ($response['code'] == 200 || $response['code'] == 201) {
            $res[$temp_class][$final_method][$final_param] = $response['body'];
        }

    }
}

// Actual Generator start here
// You can modify this section to generate controller for any framework

foreach ($class as $c) {
    $namespace = new Nette\PhpGenerator\PhpNamespace('App\Controllers\Api');

    $gclass = $namespace->addClass(ucfirst($c));
    foreach ($methods[$c] as $method) {
        $gmethod = $gclass->addMethod($method);
        foreach ($params[$c][$method] as $param) {
            if ($param != 'none') {
                $gmethod->addParameter($param);
                $gmethod->addBody("if($" . $param . "){" . PHP_EOL . PHP_EOL . "return '" . $res[$c][$method][$param] . "';" . PHP_EOL . "}");
            }
        }
        if (in_array('none', $params[$c][$method])) {
            $gmethod->addComment('@params optional')
                ->addBody(PHP_EOL . "return '" . $res[$c][$method][$param] . "';");
        }

    }
    file_put_contents('../app/Controllers/Api/' . ucfirst($c) . '.php', "<?php" . PHP_EOL . PHP_EOL . $namespace);
}
