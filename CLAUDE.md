# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A Symfony bundle that acts as a PHP client library for delivering air quality and weather station data to the Luft.jetzt API. It provides interfaces and services for submitting measurement values and managing stations programmatically.

- **Type**: Symfony Bundle (library, not standalone application)
- **Namespace**: `Caldera\LuftApiBundle`
- **PHP**: ^8.4
- **Symfony**: ^7.4 || ^8.0

## Architecture

### Key Components

- **`Api/ValueApi`** — Submits sensor values via `putValue()` / `putValues()`
- **`Api/StationApi`** — Manages stations: `getStations()`, `putStations()`, `postStations()`
- **`Client/ApiClient`** — HTTP client wrapper (GET/POST/PUT) with configurable hostname, port, SSL verification
- **`Serializer/LuftSerializer`** — JSON serialization using Symfony Serializer (DateTime as Unix timestamps, camelCase to snake_case, null values skipped)

### Bundle Configuration

Configured via `caldera_luftapi` key in Symfony config:

```yaml
caldera_luftapi:
    api:
        hostname: "luft.jetzt"   # Required
        port: 443
        verify: true             # SSL cert verification
```

Service definitions are in `src/Resources/config/services.php` (PHP-based, not XML/YAML).

### Interfaces

All main services have corresponding interfaces for dependency injection:
- `ValueApiInterface`
- `StationApiInterface`
- `ApiClientInterface`
- `LuftSerializerInterface`

## Dependencies

- `luft-jetzt/luft-model` ^0.5.1 — Shared data models (`Value`, `Station`)
- Symfony components: `http-client`, `serializer`, `dependency-injection`, `property-access`

## Common Commands

```bash
composer install   # Install dependencies
```

No tests or build steps — this is a pure library consumed by other projects (providers, luft-app).
