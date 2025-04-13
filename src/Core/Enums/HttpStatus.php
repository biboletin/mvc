<?php

namespace Bibo\Core\Enum;

enum HttpStatus: int
{
    // 2xx
    case OK = 200;
    case Created = 201;
    case Accepted = 202;
    case NonAuthoritativeInformation = 203;
    case NoContent = 204;
    case ResetContent = 205;
    case PartialContent = 206;
    // 3xx
    case MultipleChoices = 300;
    case MovedPermanently = 301;
    case Found = 302;
    case SeeOther = 303;
    case NotModified = 304;
    case UseProxy = 305;
    case TemporaryRedirect = 307;
    case PermanentRedirect = 308;
    // 4xx
    case BadRequest = 400;
    case Unauthorized = 401;
    case PaymentRequired = 402;
    case Forbidden = 403;
    case NotFound = 404;
    case MethodNotAllowed = 405;
    case NotAcceptable = 406;
    case ProxyAuthenticationRequired = 407;
    case RequestTimeout = 408;
    case Conflict = 409;
    case Gone = 410;
    case LengthRequired = 411;
    case PreconditionFailed = 412;
    case PayloadTooLarge = 413;
    case UriTooLong = 414;
    case UnsupportedMediaType = 415;
    case RangeNotSatisfiable = 416;
    case ExpectationFailed = 417;
    case ImATeapot = 418;
    case MisdirectedRequest = 421;
    case UnprocessableEntity = 422;
    case Locked = 423;
    case FailedDependency = 424;
    case TooEarly = 425;
    case UpgradeRequired = 426;
    case PreconditionRequired = 428;
    case TooManyRequests = 429;
    case RequestHeaderFieldsTooLarge = 431;
    case UnavailableForLegalReasons = 451;
    // 5xx
    case InternalServerError = 500;
    case NotImplemented = 501;
    case BadGateway = 502;
    case ServiceUnavailable = 503;
    case GatewayTimeout = 504;
    case HttpVersionNotSupported = 505;
    case VariantAlsoNegotiates = 506;
    case InsufficientStorage = 507;
    case LoopDetected = 508;
    case NotExtended = 510;
    case NetworkAuthenticationRequired = 511;
    case UnknownError = 520;
    case WebServerIsDown = 521;
    case ConnectionTimedOut = 522;
    case OriginIsUnreachable = 523;
    case ATimeoutOccurred = 524;
    case SSLHandshakeFailed = 525;
    case InvalidSSL = 526;
    case RailgunError = 527;
    case SiteIsFrozen = 530;
    case NetworkReadTimeoutError = 598;
    case NetworkConnectTimeoutError = 599;
    case Unknown = 0;

    public function message(string $locale = 'en'): string
    {
        return match ($this) {
            // 2xx: Success
            self::OK => 'OK',
            self::Created => 'Created',
            self::Accepted => 'Accepted',
            self::NonAuthoritativeInformation => 'Non-Authoritative Information',
            self::NoContent => 'No Content',
            self::ResetContent => 'Reset Content',
            self::PartialContent => 'Partial Content',

            // 3xx: Redirection
            self::MultipleChoices => 'Multiple Choices',
            self::MovedPermanently => 'Moved Permanently',
            self::Found => 'Found',
            self::SeeOther => 'See Other',
            self::NotModified => 'Not Modified',
            self::UseProxy => 'Use Proxy',
            self::TemporaryRedirect => 'Temporary Redirect',
            self::PermanentRedirect => 'Permanent Redirect',

            // 4xx: Client Error
            self::BadRequest => 'Bad Request',
            self::Unauthorized => 'Unauthorized',
            self::PaymentRequired => 'Payment Required',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Not Found',
            self::MethodNotAllowed => 'Method Not Allowed',
            self::NotAcceptable => 'Not Acceptable',
            self::ProxyAuthenticationRequired => 'Proxy Authentication Required',
            self::RequestTimeout => 'Request Timeout',
            self::Conflict => 'Conflict',
            self::Gone => 'Gone',
            self::LengthRequired => 'Length Required',
            self::PreconditionFailed => 'Precondition Failed',
            self::PayloadTooLarge => 'Payload Too Large',
            self::UriTooLong => 'URI Too Long',
            self::UnsupportedMediaType => 'Unsupported Media Type',
            self::RangeNotSatisfiable => 'Range Not Satisfiable',
            self::ExpectationFailed => 'Expectation Failed',
            self::ImATeapot => 'I\'m a teapot',
            self::MisdirectedRequest => 'Misdirected Request',
            self::UnprocessableEntity => 'Unprocessable Entity',
            self::Locked => 'Locked',
            self::FailedDependency => 'Failed Dependency',
            self::TooEarly => 'Too Early',
            self::UpgradeRequired => 'Upgrade Required',
            self::PreconditionRequired => 'Precondition Required',
            self::TooManyRequests => 'Too Many Requests',
            self::RequestHeaderFieldsTooLarge => 'Request Header Fields Too Large',
            self::UnavailableForLegalReasons => 'Unavailable For Legal Reasons',

            // 5xx: Server Error
            self::InternalServerError => 'Internal Server Error',
            self::NotImplemented => 'Not Implemented',
            self::BadGateway => 'Bad Gateway',
            self::ServiceUnavailable => 'Service Unavailable',
            self::GatewayTimeout => 'Gateway Timeout',
            self::HttpVersionNotSupported => 'HTTP Version Not Supported',
            self::VariantAlsoNegotiates => 'Variant Also Negotiates',
            self::InsufficientStorage => 'Insufficient Storage',
            self::LoopDetected => 'Loop Detected',
            self::NotExtended => 'Not Extended',
            self::NetworkAuthenticationRequired => 'Network Authentication Required',
            self::UnknownError, self::Unknown => 'Unknown Error',
            self::WebServerIsDown => 'Web Server Is Down',
            self::ConnectionTimedOut => 'Connection Timed Out',
            self::OriginIsUnreachable => 'Origin Is Unreachable',
            self::ATimeoutOccurred => 'A Timeout Occurred',
            self::SSLHandshakeFailed => 'SSL Handshake Failed',
            self::InvalidSSL => 'Invalid SSL Certificate',
            self::RailgunError => 'Railgun Error',
            self::SiteIsFrozen => 'Site Is Frozen',
            self::NetworkReadTimeoutError => 'Network Read Timeout Error',
            self::NetworkConnectTimeoutError => 'Network Connect Timeout Error',

            // Fallback for missing entries
            default => '',
        };
    }

    public function isClientError(): bool
    {
        return $this->value >= 400 && $this->value < 500;
    }

    public function isServerError(): bool
    {
        return $this->value >= 500 && $this->value < 600;
    }

    public function isSuccess(): bool
    {
        return $this->value >= 200 && $this->value < 300;
    }

    public function category(): string
    {
        return match (true) {
            $this->value >= 100 && $this->value < 200 => 'Informational',
            $this->value >= 200 && $this->value < 300 => 'Success',
            $this->value >= 300 && $this->value < 400 => 'Redirection',
            $this->value >= 400 && $this->value < 500 => 'Client Error',
            $this->value >= 500 && $this->value < 600 => 'Server Error',
            default => 'Unknown',
        };
    }

    public function isRedirection(): bool
    {
        return $this->value >= 300 && $this->value < 400;
    }

    public function isInformational(): bool
    {
        return $this->value >= 100 && $this->value < 200;
    }

    public function toArray(string $locale = 'en'): array
    {
        return [
            'code' => $this->value,
            'message' => $this->message($locale),
            'category' => $this->category(),
        ];
    }
}
