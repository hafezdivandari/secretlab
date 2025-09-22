<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: self::API_VERSION,
        title: self::PROJECT_TITLE,
        contact: new OA\Contact(name: self::DEVELOPER_NAME, email: self::DEVELOPER_EMAIL)
    ),
    servers: [
        new OA\Server(url: self::PRODUCTION_API_HOST, description: 'Staging server'),
        new OA\Server(url: self::STAGING_API_HOST, description: 'Staging server'),
        new OA\Server(url: self::LOCAL_API_HOST, description: 'Localhost'),
    ],
)]
#[OA\Response(
    response: 'InvalidData',
    description: 'The invalid data response',
    content: new OA\JsonContent(properties: [
        new OA\Property(property: 'message', type: 'string'),
        new OA\Property(property: 'errors', type: 'object'),
    ])
)]
class Docs
{
    const string PROJECT_TITLE = 'Secretlab Tech Exercise';

    const string API_VERSION = '1.0.0';

    const string DEVELOPER_NAME = 'Hafez Divandari';

    const string DEVELOPER_EMAIL = 'hafezdivandari@gmail.com';

    const string PRODUCTION_API_HOST = 'https://secretlab.hafezd.com/api';

    const string STAGING_API_HOST = 'https://secretlab.test/api';

    const string LOCAL_API_HOST = 'http://127.0.0.1:8000/api';
}
