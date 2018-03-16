<?php
return [
    'test_json'    => [
        'location' => 'json',
        'required' => true,
    ],
    'test_post'    => [
        'map'      => 'testPost',
        'location' => 'post',
    ],
    'test_header'  => [
        'location' => 'header',
    ],
    'test_uri'     => [
        'location' => 'uri',
    ],
    'test_query'   => [
        'location' => 'query',
        'static'   => false,
    ],
    'test_static'  => [
        'location' => 'uri',
        'static'   => true,
        'value'    => 'static',
    ],
    'test_static2' => [
        'location' => 'uri',
        'static'   => true,
    ],
    'test_static3' => [
        'location' => 'uri',
        'static'   => true,
        'value'    => 'static3',
    ],
];