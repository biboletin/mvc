<?php

namespace Bibo\Core\Enum;

enum HttpStatus: int
{
    /**
     * 200 OK
     * Standard response for successful HTTP requests.
     * The actual response will depend on the request method used:
     * - GET: The resource has been fetched and is transmitted in the message body.
     * - HEAD: The entity headers are in the message body.
     * - PUT or POST: The resource describing the result of the action is transmitted in the message body.
     */
    case OK = 200;

    /**
     * 201 Created
     * The request has been fulfilled, resulting in the creation of a new resource.
     * Typically used as a response to POST or PUT requests.
     */
    case Created = 201;

    /**
     * 202 Accepted
     * The request has been accepted for processing, but the processing has not been completed.
     * The request might or might not be eventually acted upon, and may be disallowed when processing occurs.
     */
    case Accepted = 202;

    /**
     * 203 Non-Authoritative Information
     * The server is a transforming proxy that received a 200 OK from its origin,
     * but is returning a modified version of the origin's response.
     */
    case NonAuthoritativeInformation = 203;

    /**
     * 204 No Content
     * The server successfully processed the request, but is not returning any content.
     * Usually used as a response to a successful DELETE request.
     */
    case NoContent = 204;

    /**
     * 205 Reset Content
     * The server successfully processed the request, but is not returning any content.
     * Unlike 204, this response requires the requester to reset the document view.
     */
    case ResetContent = 205;

    /**
     * 206 Partial Content
     * The server is delivering only part of the resource due to a range header sent by the client.
     * Used for resumable downloads or split downloads.
     */
    case PartialContent = 206;

    // 3xx - Redirection: Further action needs to be taken in order to complete the request

    /**
     * 300 Multiple Choices
     * Indicates multiple options for the resource from which the client may choose.
     * For example, this could be used to present different format options for video or list of file formats.
     */
    case MultipleChoices = 300;

    /**
     * 301 Moved Permanently
     * This and all future requests should be directed to the given URI.
     * Search engines update their links to the resource.
     */
    case MovedPermanently = 301;

    /**
     * 302 Found (Previously "Moved Temporarily")
     * Tells the client to look at another URL. 302 has been superseded by 303 and 307.
     * This is an example of industry practice contradicting the standard.
     */
    case Found = 302;

    /**
     * 303 See Other
     * The response to the request can be found under another URI using the GET method.
     * When received in response to a POST, PUT, or DELETE, it should be assumed that
     * the server has received the data and the redirect should be issued with a GET.
     */
    case SeeOther = 303;

    /**
     * 304 Not Modified
     * Indicates that the resource has not been modified since the version specified by the request headers.
     * There is no need to retransmit the resource since the client still has a previously-downloaded copy.
     */
    case NotModified = 304;

    /**
     * 305 Use Proxy
     * The requested resource is available only through a proxy, the address for which is provided in the response.
     * Many HTTP clients (such as Mozilla Firefox and Internet Explorer) do not correctly handle responses with this status code.
     * @deprecated Due to security concerns regarding in-band configuration of a proxy
     */
    case UseProxy = 305;

    /**
     * 307 Temporary Redirect
     * In this case, the request should be repeated with another URI; however, future requests should still use the original URI.
     * In contrast to 302, the request method should not be changed when reissuing the original request.
     */
    case TemporaryRedirect = 307;

    /**
     * 308 Permanent Redirect
     * This and all future requests should be directed to the given URI.
     * 308 parallel the behavior of 301, but does not allow the HTTP method to change.
     */
    case PermanentRedirect = 308;

    // 4xx - Client Error: The request contains bad syntax or cannot be fulfilled

    /**
     * 400 Bad Request
     * The server cannot or will not process the request due to an apparent client error
     * (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
     */
    case BadRequest = 400;

    /**
     * 401 Unauthorized
     * Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
     * The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource.
     */
    case Unauthorized = 401;

    /**
     * 402 Payment Required
     * Reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme.
     */
    case PaymentRequired = 402;

    /**
     * 403 Forbidden
     * The request was valid, but the server is refusing action.
     * The user might not have the necessary permissions for a resource, or may need an account of some sort.
     */
    case Forbidden = 403;

    /**
     * 404 Not Found
     * The requested resource could not be found but may be available in the future.
     * Subsequent requests by the client are permissible.
     */
    case NotFound = 404;

    /**
     * 405 Method Not Allowed
     * A request method is not supported for the requested resource;
     * for example, a GET request on a form that requires data to be presented via POST, or a PUT request on a read-only resource.
     */
    case MethodNotAllowed = 405;

    /**
     * 406 Not Acceptable
     * The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.
     */
    case NotAcceptable = 406;

    /**
     * 407 Proxy Authentication Required
     * The client must first authenticate itself with the proxy.
     */
    case ProxyAuthenticationRequired = 407;

    /**
     * 408 Request Timeout
     * The server timed out waiting for the request.
     * The client did not produce a request within the time that the server was prepared to wait.
     */
    case RequestTimeout = 408;

    /**
     * 409 Conflict
     * Indicates that the request could not be processed because of conflict in the current state of the resource,
     * such as an edit conflict between multiple simultaneous updates.
     */
    case Conflict = 409;

    /**
     * 410 Gone
     * Indicates that the resource requested is no longer available and will not be available again.
     * This should be used when a resource has been intentionally removed and the resource should be purged.
     */
    case Gone = 410;

    /**
     * 411 Length Required
     * The request did not specify the length of its content, which is required by the requested resource.
     */
    case LengthRequired = 411;

    /**
     * 412 Precondition Failed
     * The server does not meet one of the preconditions that the requester put on the request.
     */
    case PreconditionFailed = 412;

    /**
     * 413 Payload Too Large
     * The request is larger than the server is willing or able to process.
     */
    case PayloadTooLarge = 413;

    /**
     * 414 URI Too Long
     * The URI provided was too long for the server to process.
     */
    case UriTooLong = 414;

    /**
     * 415 Unsupported Media Type
     * The request entity has a media type which the server or resource does not support.
     */
    case UnsupportedMediaType = 415;

    /**
     * 416 Range Not Satisfiable
     * The client has asked for a portion of the file, but the server cannot supply that portion.
     */
    case RangeNotSatisfiable = 416;

    /**
     * 417 Expectation Failed
     * The server cannot meet the requirements of the Expect request-header field.
     */
    case ExpectationFailed = 417;

    /**
     * 418 I'm a teapot
     * Any attempt to instruct an teapot to brew coffee should result in the error code "418 I'm a teapot".
     * This is an April Fools' joke defined in RFC 2324.
     */
    case ImATeapot = 418;

    /**
     * 421 Misdirected Request
     * The request was directed at a server that is not able to produce a response.
     * This can be sent by a server that is not configured to produce responses for the combination of scheme and authority in the URI.
     */
    case MisdirectedRequest = 421;

    /**
     * 422 Unprocessable Entity
     * The request was well-formed but was unable to be followed due to semantic errors.
     */
    case UnprocessableEntity = 422;

    /**
     * 423 Locked
     * The resource that is being accessed is locked.
     */
    case Locked = 423;

    /**
     * 424 Failed Dependency
     * The request failed because it depended on another request and that request failed.
     */
    case FailedDependency = 424;

    /**
     * 425 Too Early
     * Indicates that the server is unwilling to risk processing a request that might be replayed.
     */
    case TooEarly = 425;

    /**
     * 426 Upgrade Required
     * The client should switch to a different protocol such as TLS/1.0, given in the Upgrade header field.
     */
    case UpgradeRequired = 426;

    /**
     * 428 Precondition Required
     * The origin server requires the request to be conditional.
     * Intended to prevent the 'lost update' problem, where a client GETs a resource's state,
     * modifies it, and PUTs it back to the server, when meanwhile a third party has modified the state on the server.
     */
    case PreconditionRequired = 428;

    /**
     * 429 Too Many Requests
     * The user has sent too many requests in a given amount of time ("rate limiting").
     */
    case TooManyRequests = 429;

    /**
     * 431 Request Header Fields Too Large
     * The server is unwilling to process the request because its header fields are too large.
     * The request may be resubmitted after reducing the size of the request header fields.
     */
    case RequestHeaderFieldsTooLarge = 431;

    /**
     * 451 Unavailable For Legal Reasons
     * A server operator has received a legal demand to deny access to a resource or to a set of resources
     * that includes the requested resource. The code is named after Ray Bradbury's novel Fahrenheit 451.
     */
    case UnavailableForLegalReasons = 451;

    /**
     * 500 Internal Server Error
     * A generic error message, given when an unexpected
     * condition was encountered and no more specific message is suitable.
     */
    case InternalServerError = 500;

    /**
     * 501 Not Implemented
     * The server either does not recognize the request method, or it lacks the ability to fulfill the request.
     * Usually this implies future availability (e.g., a new feature of a web-service API).
     */
    case NotImplemented = 501;

    /**
     * 502 Bad Gateway
     * The server was acting as a gateway or proxy and received an invalid response from the upstream server.
     */
    case BadGateway = 502;

    /**
     * 503 Service Unavailable
     * The server is currently unavailable (because it is overloaded or down for maintenance).
     * Generally, this is a temporary state.
     */
    case ServiceUnavailable = 503;

    /**
     * 504 Gateway Timeout
     * The server was acting as a gateway or proxy and did not receive a timely response from the upstream server.
     */
    case GatewayTimeout = 504;

    /**
     * 505 HTTP Version Not Supported
     * The server does not support the HTTP protocol version used in the request.
     */
    case HttpVersionNotSupported = 505;

    /**
     * 506 Variant Also Negotiates
     * Transparent content negotiation for the request results in a circular reference.
     */
    case VariantAlsoNegotiates = 506;

    /**
     * 507 Insufficient Storage
     * The server is unable to store the representation needed to complete the request.
     */
    case InsufficientStorage = 507;

    /**
     * 508 Loop Detected
     * The server detected an infinite loop while processing the request.
     */
    case LoopDetected = 508;

    /**
     * 510 Not Extended
     * Further extensions to the request are required for the server to fulfill it.
     */
    case NotExtended = 510;

    /**
     * 511 Network Authentication Required
     * The client needs to authenticate to gain network access.
     * Intended for use by intercepting proxies used to control access to the network.
     */
    case NetworkAuthenticationRequired = 511;

    /**
     * 520 Unknown Error
     * A custom status code used by some providers to indicate when a server has returned an unknown error.
     */
    case UnknownError = 520;

    /**
     * 521 Web Server Is Down
     * A custom status code used by some providers to indicate when the origin web server is down.
     */
    case WebServerIsDown = 521;

    /**
     * 522 Connection Timed Out
     * A custom status code used by some providers to indicate when the connection to the origin web server timed out.
     */
    case ConnectionTimedOut = 522;

    /**
     * 523 Origin Is Unreachable
     * A custom status code used by some providers to indicate when the origin web server is unreachable.
     */
    case OriginIsUnreachable = 523;

    /**
     * 524 A Timeout Occurred
     * A custom status code used by some providers to indicate when a timeout occurred.
     */
    case ATimeoutOccurred = 524;

    /**
     * 525 SSL Handshake Failed
     * A custom status code used by some providers to indicate when the SSL handshake between the edge server and origin failed.
     */
    case SSLHandshakeFailed = 525;

    /**
     * 526 Invalid SSL Certificate
     * A custom status code used by some providers to indicate when the origin web server's SSL certificate is invalid.
     */
    case InvalidSSL = 526;

    /**
     * 527 Railgun Error
     * A custom status code used by some providers to indicate when a request timed out or failed after the WAN connection was established.
     */
    case RailgunError = 527;

    /**
     * 530 Site Is Frozen
     * A custom status code used by some providers to indicate when a site has been frozen due to a legal hold.
     */
    case SiteIsFrozen = 530;

    /**
     * 598 Network Read Timeout Error
     * A custom status code used to indicate when the network read timeout occurred behind the proxy to a client.
     */
    case NetworkReadTimeoutError = 598;

    /**
     * 599 Network Connect Timeout Error
     * A custom status code used to indicate when a network connect timeout occurred behind the proxy to a client.
     */
    case NetworkConnectTimeoutError = 599;

    /**
     * 0 Unknown
     * Represents an unknown HTTP status code.
     */
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
