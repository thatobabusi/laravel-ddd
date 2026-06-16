# Generators & Commands

## Generator Syntax
All commands follow this pattern:
```bash
php artisan ddd:{object} {domain}:{name}
```

## Core Generators
| Command | Description |
|---|---|
| `ddd:model` | Domain model (supports `-mfs` etc) |
| `ddd:dto` | Data Transfer Object |
| `ddd:action` | Business logic action |
| `ddd:view-model` | View model |
| `ddd:value` | Value object |

## Utility Commands
| Command | Description |
|---|---|
| `ddd:list` | Summary of current domains |
| `ddd:optimize` | Cache domain manifests |
| `ddd:clear` | Clear domain cache |
| `ddd:stub` | Manage/publish stubs |
