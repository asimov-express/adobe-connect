<?php

function process_api_request()
{
    $parameters = getParamsFromXML();

    if (isset($parameters['action'])) {
        respondToAction($parameters);
    } else {
        sendBadRequestResponse();
    }
}

function getParamsFromXML()
{
    $data = file_get_contents('php://input');

    $xml = new SimpleXMLElement($data);
    $nodes = $xml->xpath('/params/param');

    $parameters = [];

    foreach ($nodes as $param) {
        $param_name = $param->attributes()['name']->__toString();
        $param_value = $param->__toString();
        $parameters[$param_name] = $param_value;
    }

    return $parameters;
}

function sendBadRequestResponse()
{
    sendResponse(400, 'Bad request');
}

function sendResponse($code, $content)
{
    http_response_code($code);
    print($content);
}

function respondToAction($parameters)
{

    $action = $parameters['action'];

    if (isValidAction($action)) {
        $function_name = 'adobeConnect'. ucfirst($action);
        call_user_func($function_name, $parameters);
    } else {
        $body = readData('invalid_action');
        sendResponse(200, $body);
    }
}

function adobeConnectLogin($parameters)
{
    $login = $parameters['login'];
    $password = $parameters['password'];

    if ($login ==='fakeuser@example.com' && $password === 'secret') {
        // Set-Cookie:BREEZESESSION=na5breezk72mn3prtcu9czm5;HttpOnly;domain=.adobeconnect.com;path=/
        // Set-Cookie:BreezeCCookie=MAU7-9BPA-BD9L-TUHZ-YRSD-U64C-UGMP-R2B5; Path=/; HttpOnly
        $body = readData('login_correct');
        sendResponse(200, $body);
    } else {
        $body = readData('login_incorrect');
        sendResponse(200, $body);
    }
}

function adobeConnectLogout($parameters)
{
    $body = readData('logout_correct');
    sendResponse(200, $body);
}

function readData($file)
{
    return file_get_contents(__DIR__."/data/{$file}.xml");
}

function isValidAction($action)
{
    $actions = [
        'login' => true,
        'logout' => true
    ];

    return isset($actions[$action]);
}
