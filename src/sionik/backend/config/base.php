<?php
return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'components' => [
        'request' => [
          //  'baseUrl' => 'backend.localhost',
          //  'enableCsrfValidation' => false,
        ],
        'urlManager' => require __DIR__ . '/_urlManager.php',
        'frontendCache' => require Yii::getAlias('@frontend/config/_cache.php')
    ],
];
