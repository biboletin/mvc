<?php

namespace Bibo\Mvc\Core\Enums;

/**
 * HTTP protocol version
 */
enum HttpProtocolVersion: string
{
    /*
     * Version 1.1
     */
    case V1 = '1.1';

    /*
     * Version 2
     * http2
     */
    case V2 = '2.0';
}
