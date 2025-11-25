# .env — Local Environment Variables for This Project

This file explains how to use environment variables in this project (local `.env` files and Apache vhost settings).

This project provides a tiny `.env` loader (see `bootstrap/LoadEnv.php`) which reads a `local/.env` file and publishes the values into the process environment using `putenv`.

---

### Quick summary

- The console entrypoint (`yii`) automatically loads environment variables by calling  
  `LoadEnv::load(__DIR__ . '/local/.env')`, so all console commands have access to the variables defined in `local/.env`.
- `LoadEnv` is intended **only for console use**. Web requests do **not** load `local/.env` unless you explicitly modify `web/index.php` (not recommended for production; use vhost/FPM config instead).
- `LoadEnv::load()` does not overwrite existing environment values, and it will throw an exception if the file is missing or contains malformed lines.

---

### `.env` format and rules (what `LoadEnv` enforces)

Example `local/.env`:

```
APP_ENV=dev
DB_DSN="mysql:host=127.0.0.1;dbname=project_db"
DB_USERNAME=project_db_user
DB_PASSWORD=secret
```

---

### Console use (already wired up)

- The `yii` console entrypoint in the project root already invokes the loader:

```php
// /yii (console bootstrap)
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/bootstrap/LoadEnv.php';
use bootstrap\LoadEnv;
LoadEnv::load(__DIR__ . '/local/.env');
```

- Because the loader is called early, console commands can use `getenv('DB_NAME')`, or your framework config can use `getenv()` to configure database connections, queue transports, cache backends, etc.

Example commands:

```powershell
php yii migrate/up --interactive=0
php yii cache/flush-all
php yii user/create --email=foo@example.com
```

If you need the loader to read a different file or a different path for console, edit the `yii` file and change the `LoadEnv::load()` call to point to the correct file.

---

# Web / Apache vhost: two common approaches

Set environment variables in the Apache virtual host (preferred for production or when you don't want to store secrets in code).

For WAMP / Apache with mod_php (Windows), add `SetEnv` entries in the vhost configuration:

```
<VirtualHost *:80>
	ServerName server.local
	ServerAlias server.localhost
	ServerAlias 192.168.0.100
	DocumentRoot "C:/wamp64/www/BookShare/web"
	SetEnv DB_HOST localhost
	SetEnv DB_NAME my_project_db
	SetEnv DB_USER root
	SetEnv DB_PASSWORD secret
	SetEnv API_PORT 3306
	SetEnv SALT "****SECRET_SALT2****"
  <Directory "C:/wamp64/www/my_project/web/">
    Options +Indexes +Includes +FollowSymLinks +MultiViews
    AllowOverride All
    <RequireAny>
      Require local
    </RequireAny>
  </Directory>
 ErrorLog "c:/wamp64/logs/my_project.local-error.log"
 CustomLog "c:/wamp64/logs/my_project.local-access.log" common
</VirtualHost>
```

After adding env variables to the vhost, restart Apache for them to take effect. PHP’s getenv('DB_DSN') will return these values.

Note for Apache with PHP-FPM / FastCGI: `SetEnv` may not pass env vars to the PHP process by default. For PHP-FPM, prefer the pool configuration (e.g., `www.conf`):

- Add env entries:
  ```
  env[DB_DSN] = "mysql:host=127.0.0.1;dbname=my_project"
  env[DB_USERNAME] = root
  env[DB_PASSWORD] = secret
  ```
- Or set `clear_env = no` and add `env[...] = value` entries in the pool config.

Restart FPM after changes so PHP processes pick up the vars.

---

### Security & best practices

- Do not commit `local/.env` to version control; add it to `.gitignore` if you haven't already (this project currently doesn't ignore `/local/.env`).
- A sample file `local/.env-sample` is provided so you can copy it to `local/.env` and fill in your own environment values.
- Store sensitive secrets (DB passwords, API keys) using your host's secure secret storage or the vhost/fpm pool configuration in production.
- Ensure `local/.env` is readable by the webserver but not world-readable if your host supports finer file permissions.


---

### Troubleshooting

- If `getenv()` returns `false`, check the following:
  - Ensure the `.env` file exists at `local/.env` and is readable by the console process (or the webserver if you manually enabled loading for web requests).
  - For web requests, confirm that you explicitly added `LoadEnv::load()` to `web/index.php`, or that your Apache/Nginx vhost defines the environment variables (restart Apache/Nginx after changes).
  - For PHP-FPM setups, verify the pool configuration for `env[...]` entries and restart the FPM service after modifying them.
- If values appear incorrectly quoted, make sure that any quoted values use matching surrounding quotes. `LoadEnv` strips matching quotes but does not parse escape sequences.
- Use `phpinfo()` or `var_dump(getenv('MY_VAR'))` to inspect the environment and debug issues.


---

If you want, I can add a short unit test for `bootstrap/LoadEnv.php` to validate parsing rules and edge cases, or add a `web/index.php` patch that safely loads `local/.env` for web requests.
