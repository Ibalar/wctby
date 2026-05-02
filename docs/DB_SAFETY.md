# Database Safety

Destructive database commands are blocked by default outside safe environments.

Blocked commands:

- `migrate:fresh`
- `migrate:refresh`
- `migrate:reset`
- `db:wipe`
- `php artisan test` with `--drop-databases` or `--recreate-databases`

To run intentionally, set:

- `ALLOW_DESTRUCTIVE_DB_COMMANDS=true` for the current command/session.

Examples:

```bash
php artisan test
ALLOW_DESTRUCTIVE_DB_COMMANDS=true php artisan migrate:fresh
```
