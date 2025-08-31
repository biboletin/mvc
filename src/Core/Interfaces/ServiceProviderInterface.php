<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface ServiceProviderInterface
 *
 * This interface defines the contract for service providers in the Biboletin framework.
 * Service providers are responsible for registering services and bootstrapping them.
 */
interface ServiceProviderInterface
{
    /**
     * Register services with the application.
     *
     * This method is called to register any services or bindings that the service provider provides.
     */
    public function register();

    /**
     * Boot the service provider.
     *
     * This method is called after all service providers have been registered.
     * It can be used to perform any additional setup or configuration.
     */
    public function boot();
}
