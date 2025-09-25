# 📘 Bibo MVC Framework Roadmap

This roadmap reflects the current state of the Bibo MVC framework and outlines what is complete, what remains, and recommendations for next steps.

---

## 🔎 Status Summary (Done vs To Do)

Done (highlights):
- PSR-4 autoloading, DI container (PSR-11), PSR-7/15 HTTP stack, middleware pipeline, response emitter
- Router matching via composite strategies: Exact, Regex, and Cached Regex (with pattern caching)
- Advanced router with named routes, parameters, groups, middleware per route/group, resource routes
- Service provider architecture and boot cycle (see bootstrap/bootstrap.php); providers emit boot logs via LogManager
- Twig templating, view layer, error handling, CSRF middleware, session provider
- File cache (PSR-16), logging system with LogManager, rotating file handler and contextual logging
- Rich HTTP request helper traits (RequestHelper, InputHelper, SecurityHelper, UrlHelper, HttpContentHelper); improved JsonResponse and ResponseEmitter
- CLI tooling (console kernel + registered commands), configuration files and .env support
- Illuminate Database package installed; configuration scaffold present
- Static analysis (PHPStan), code style tooling configured (PHPCS), PHPUnit config present

To Do (highlights):
- Event dispatcher (PSR-14 or custom), task scheduler/cron integration
- Authentication (guards), authorization/permissions (RBAC/policies)
- Migrations/seeds workflow wire-up and developer commands
- Debug toolbar/profiler, code coverage, CI pipeline
- Optional modules system and admin panel

---

## ✅ Phase 1: Core Foundations

- [x] PSR-16 FileCache (file-based; optional encryption available via Crypto services)
- [x] PSR-4 Autoloading via Composer
- [x] Central configuration system (`config/*.php` + `.env` support)
- [x] Exception and error handling integration
- [x] CLI tooling entry point (`./console`) and command registration

---

## 🧱 Phase 2: HTTP Core

- [x] PSR-7 HTTP Messages (Request, Response, URI, Stream)
- [x] PSR-15 Middleware system and dispatcher
- [x] Router with: named routes, parameters, groups/prefixes, per-route/group middleware, resource routes
- [x] Response emitter service
- [x] Error handler with environment awareness (dev/prod)

---

## 📦 Phase 3: Services & Utilities

- [x] PSR-3 Logger (custom) with rotating file handler and context
- [x] PSR-11 Dependency Injection container
- [x] Service provider support (register + boot lifecycle)
- [ ] Event dispatcher (PSR-14 or custom)
- [~] Task system (base tasks exist); scheduler/cron integration and CLI UX pending

Legend: [~] = partial/in progress

---

## 🧰 Phase 4: Developer Experience

- [x] Twig template engine integration
- [x] CLI command tool (console kernel + commands in app/Commands and bootstrap/commands.php)
- [x] `.env` support with config fallbacks
- [ ] Debug bar / profiler
- [ ] Modular structure (`Modules/User`, `Modules/Admin`, etc.)

---

## 🔐 Phase 5: Security & Sessions

- [x] CSRF protection (middleware + helpers)
- [x] Session management (service provider + config)
- [ ] Authentication (basic / token / JWT)
- [ ] Authorization / Permission middleware (roles, policies, gates)

---

## 🌐 Phase 6: Database & ORM

- [x] Database library available (illuminate/database installed)
- [ ] Connection bootstrap and service provider wiring for DB
- [ ] Migrations system and CLI commands
- [ ] Seeder and data factory support
- [ ] Optional: lightweight repository/ORM helpers on top of Eloquent

---

## 🧪 Phase 7: Testing & CI

- [x] PHPUnit scaffold (phpunit.xml) and tests/ directory
- [x] Static analysis: PHPStan (configured), Psalm config available
- [ ] Code style: auto-fix (PHP-CS-Fixer) or PHPCBF integration in CI
- [ ] Code coverage tools (Xdebug/PCOV) and reporting
- [ ] CI pipeline (GitHub Actions) for tests, static analysis, linting, and build

---

## 🎯 Optional: Admin Panel / CMS

- [ ] Web admin panel (users, roles, logs)
- [ ] CMS module (pages, menus)
- [ ] Web-based config editor
- [ ] Cache and log viewers

---

## 📦 PSR Standards Being Followed

- [x] PSR-1 / PSR-12: Coding standards (phpcs.xml provided)
- [x] PSR-3: Logger
- [x] PSR-4: Autoloading
- [x] PSR-7: HTTP messages
- [x] PSR-11: Container
- [x] PSR-15: HTTP Server Middleware
- [x] PSR-16: Simple Cache
- [ ] PSR-6: Caching (optional)
- [ ] PSR-14: Events (optional)
- [ ] PSR-17/18: HTTP factories/client compliance (interfaces installed; full compliance TBD)

---

## 💡 Suggestions & Next Steps

Short term:
- Implement a lightweight event dispatcher (PSR-14) and wire provider/bootstrapping
- Finalize database wiring: connection manager, migration runner, seeder CLI commands
- Add Auth middleware + guard abstraction; basic session auth with login/logout routes
- Introduce a minimal debug toolbar (request timeline, logs, config/env snapshot)

Medium term:
- CI pipeline (GitHub Actions) running: composer validate, phpstan, psalm, phpcs, phpunit, build assets
- Add code coverage and quality gates; integrate PHPCBF/PHP-CS-Fixer auto-fixes
- Provide maker commands: make:controller, make:model, make:migration, make:seeder

Long term / optional:
- Modules/packages system with service provider auto-discovery
- Admin panel: users/roles/permissions, logs viewer, cache viewer
- Publish a starter template and documentation site (docs/) with examples

---

_Updated: 2025-09-26 02:45_
