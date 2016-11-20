<?php

function main()
{
    $parameters = getParamsFromXML();

    if (validateParams($parameters)) {
        // Send predefined answer
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

function validateParams($parameters)
{
    if (!isset($parameters['action'])) {
        http_response_code(400);
        return false;
    }
    return true;
}
