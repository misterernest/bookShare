<?php
namespace bootstrap;

/**
 * Simple .env loader utility.
 *
 * This class provides a tiny loader to read environment variables from a file
 * (commonly named `.env`) and publish them into the process environment
 * using `putenv`. It deliberately does not overwrite existing environment
 * variables and performs a few sanity checks to help catch malformed files.
 */
class LoadEnv
{
    /**
     * Load environment variables from a file at $path.
     *
     * Rules enforced by the loader:
     *  - The file must exist and be readable.
     *  - Empty lines and lines beginning with `#` or `;` are treated as comments.
     *  - Each non-comment line must contain an `=` separator.
     *  - The left-hand side (key) must match `/^[A-Z0-9_]+$/i`.
     *  - Values wrapped in single or double quotes will have their surrounding quotes
     *    stripped but no escape sequence parsing is performed.
     *  - Existing environment variables (from `getenv`) are not overwritten.
     *
     * Note: the loader uses `putenv` to set values for the current process only.
     * Commented out lines show how you could also inject into `$_ENV`/`$_SERVER` if
     * that was desired by your application.
     *
     * @param string $path Absolute or relative path to the env file to parse
     * @throws \RuntimeException If the file cannot be opened or a malformed line is found
     */
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Env file not found: $path");
        }

        if (!is_readable($path)) {
            throw new \RuntimeException("ENV file is not readable: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $lineNumber => $line) {
            // Trim whitespace so we can reliably detect emptiness and comments
            $line = trim($line);

            // Skip empty lines and comment lines starting with `#` or `;`.
            if ($line === '' || $line[0] === '#' || $line[0] === ';') {
                continue;
            }

            // Each valid line must contain an equals sign separating key and value
            if (!str_contains($line, '=')) {
                throw new \RuntimeException(
                    "Invalid line in env file at line " . ($lineNumber + 1) . ": $line");
            }

            // Split into key and value (only split on the first `=` to preserve any `=` in the value)
            [$key, $value] = array_map('trim', explode('=', $line, 2));

            // Enforce variable-name format (letters, numbers, underscores). Case-insensitive.
            if (!preg_match('/^[A-Z0-9_]+$/i', $key)) {
                throw new \RuntimeException(
                    "Invalid environment variable name at line " . ($lineNumber + 1) . ": $key");
            }

            // Remove matching surrounding single or double quotes from the value
            // (do not process escapes — this is intentionally simple).
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            // If the variable is already defined in the environment, leave it as-is.
            // This avoids surprising overwrites in hosting environments where values
            // may be injected by the runtime or web server configuration.
            if (getenv($key) !== false) {
                continue;
            }

            // Publish the value into the environment for the current process.
            // The loader does not automatically propagate values to `$_ENV`/`$_SERVER` to
            // avoid unexpected global side-effects; uncomment the lines below if that
            // behavior is preferred.
            putenv("$key=$value");

            // $_SERVER[$key] = $value;
            // $_ENV[$key] = $value;
        }
        
    }
}