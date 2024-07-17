<?php

namespace Biboletin\Mvc;

use Biboletin\Mvc\Controller;

class AjaxHandler extends Controller
{
    private array $data = [];
    private string $method;
    private string $action;
    private string $controller;
    private string $response;
    private string $error;
    private string $success;
    private bool $isAjax = false;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $this->action = $_POST['action'] ?? '';
        $this->controller = $_POST['controller'] ?? '';
        $this->response = $_POST['response'] ?? '';
        $this->error = $_POST['error'] ?? '';
        $this->success = $_POST['success'] ?? '';
    }

    public function handleRequest(): void
    {
        if ($this->isAjax === true) {
            $this->data = $this->getAjaxData();
            $this->dispatch();
        }
    }

    private function getAjaxData(): array
    {
        $data = [];
        $data['action'] = $this->action;
        $data['controller'] = $this->controller;
        $data['response'] = $this->response;
        $data['error'] = $this->error;
        $data['success'] = $this->success;
        return $data;
    }

    private function dispatch(): void
    {
        $controller = $this->controller;
        $action = $this->action;
        $response = $this->response;
        $error = $this->error;
        $success = $this->success;
        $data = $this->data;

        if (class_exists($controller) === true) {
            $controller = new $controller();
            if (method_exists($controller, $action) === true) {
                $controller->$action($data);
            } else {
                $this->sendResponse($response, $error);
            }
        } else {
            $this->sendResponse($response, $error);
        }
    }

    private function sendResponse(string $response, string $error): void
    {
        if ($response !== '') {
            echo $response;
        } else {
            echo $error;
        }
    }

    public function isAjax(): bool
    {
        return $this->isAjax;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getSuccess(): string
    {
        return $this->success;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function setSuccess(string $success): void
    {
        $this->success = $success;
    }

    public function setIsAjax(bool $isAjax): void
    {
        $this->isAjax = $isAjax;
    }

    public function __destruct()
    {
        $this->handleRequest();
    }
}
