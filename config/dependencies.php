<?php

use Bibo\Core\BaseRouter\BaseRouter;
use Bibo\Core\Request\BaseRequest;

return [
    BaseRouter::class => function () {
        return new BaseRouter();
    },
    BaseRequest::class => function () {
        return new BaseRequest();
    },
];
