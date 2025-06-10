# 📘 Biboletin Framework Roadmap

This roadmap outlines the features and development milestones for the Biboletin PHP framework.

---

## ✅ Phase 1: Core Foundations

- [x] PSR-16 FileCache with optional encryption
- [x] PSR-4 Autoloading via Composer
- [x] Exception handling (try/catch)
- [ ] Central configuration system (`config/*.php` or `.env`)
- [ ] CLI config loader and environment manager

---

## 🧱 Phase 2: HTTP Core

- [x] PSR-7 HTTP Message (Request, Response, URI, Stream)
- [x] PSR-15 Middleware system
- [x] Basic router
- [ ] Named routes and route parameters
- [ ] Route groups and prefixes
- [ ] Middleware support per route/group
- [ ] Response emitter
- [ ] Error handler (dev/prod support)

---

## 📦 Phase 3: Services & Utilities

- [x] PSR-3 Logger (custom)
- [x] Log rotation, formatters, contextual logging
- [x] PSR-11 Dependency Injection container
- [ ] Service provider support
- [ ] Event dispatcher (PSR-14 or custom)
- [ ] Task scheduler (optional)

---

## 🧰 Phase 4: Developer Experience

- [x] Twig template engine integration
- [ ] CLI command tool (e.g., `php biboletin make:controller`)
- [ ] Debug bar / profiler
- [ ] `.env` support + config fallback
- [ ] Modular structure (`Modules/User`, `Modules/Admin`, etc.)

---

## 🔐 Phase 5: Security & Sessions

- [x] Role-based access control middleware
- [ ] CSRF protection
- [ ] Session management
- [ ] Authentication (basic / token / JWT)
- [ ] Permission middleware

---

## 🌐 Phase 6: Database & ORM

- [ ] PDO/DBAL database connection
- [ ] Query builder
- [ ] Lightweight ORM or Eloquent-style integration
- [ ] Migration system
- [ ] Seeder and data factory support

---

## 🧪 Phase 7: Testing & CI

- [ ] PHPUnit or Pest setup
- [x] PHPStan static analysis
- [ ] PHP-CS-Fixer for code style
- [ ] Code coverage tools
- [ ] GitHub Actions for CI

---

## 🎯 Optional: Admin Panel / CMS

- [ ] Web admin panel (users, roles, logs)
- [ ] CMS module (pages, menus)
- [ ] Web-based config file editor
- [ ] Cache viewer and log viewer

---

## 📦 PSR Standards Being Followed

- [x] PSR-1 / PSR-12: Coding standards
- [x] PSR-3: Logger interface
- [x] PSR-4: Autoloading standard
- [x] PSR-7: HTTP message interface
- [x] PSR-11: Container interface
- [x] PSR-15: Middleware interface
- [x] PSR-16: Simple Cache interface
- [ ] PSR-6: Advanced Caching (optional)
- [ ] PSR-14: Events (optional)
- [ ] PSR-17/18: HTTP factories/client (optional)

---

## 🛠️ Contributing Guidelines (to be added)

- [ ] Code style rules (PHP-CS-Fixer config)
- [ ] PR review checklist
- [ ] Issue template
- [ ] Contributing guide

---

_Updated: {{TODAY}}_
