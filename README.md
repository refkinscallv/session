Here's the `README.md` for the `Session` library, including installation instructions, sample `.env` file, and usage details:

---

# Session Library with Timeout and Flash Data Support

The `Session` class provides advanced session management with features like automatic session timeout handling, flash data storage, and more. This library allows you to manage session values easily and securely.

---

## Features

- **Session Timeout**: Automatically expires sessions after a specified timeout period.
- **Flash Data**: Store and retrieve flash data that persists for a single session request.
- **Configurable Session Parameters**: Customize session name, timeout, path, domain, and secure flags.
- **Easy-to-Use API**: Manage session data with simple methods for setting, getting, and removing session values.

---

## Installation

1. **Install the Package via Composer**

   To install the `refkinscallv/session` package, run the following command:

   ```bash
   composer require refkinscallv/session
   ```

   This command will download and install the library along with its dependencies.

2. **Include the Composer Autoloader**

   Make sure to include the Composer autoloader at the top of your PHP scripts to automatically load the classes:

   ```php
   require_once 'vendor/autoload.php';
   ```

3. **Environment Configuration**

   Make sure that your environment variables are properly configured for session management. You can do this by creating a `.env` file in the root directory of your project (if not already present) with the following contents:

   **Sample `.env` File**:

   ```env
   SESSION_NAME=web_session
   SESSION_TIMEOUT=1
   SESSION_PATH=/
   SESSION_SECURE=false
   ```

   This file defines:
   - `SESSION_NAME`: The name of the session.
   - `SESSION_TIMEOUT`: The session timeout duration in hours (default is 1 hour).
   - `SESSION_PATH`: The path on the server where the session is available.
   - `SESSION_SECURE`: Whether the session should be marked as secure (use HTTPS only). Set to `false` by default.

4. **Using the Library**

   After installing the package and configuring your environment, you can now begin using the `Session` class in your project.

---

## Usage

### Basic Setup

```php
use RF\Session\Session;

// Initialize the session
$session = new Session();

// Start the session
$session->start();

// Set a session value
$session->set('user', 'John Doe');

// Get a session value
echo $session->get('user'); // Outputs: John Doe

// Check if a session key exists
if ($session->has('user')) {
    echo 'User session is set.';
}

// Get multiple session values
$values = $session->some(['user', 'role']);
echo $values['user']; // Outputs: John Doe

// Set flash data
$session->setFlash('message', 'Session started successfully!');

// Get and remove flash data
echo $session->getFlash('message'); // Outputs: Session started successfully!
```

---

## Configuration

### Constructor Parameters

```php
public function __construct()
```

- **No parameters**: The constructor automatically reads environment variables from the server and configures the session accordingly.

### Environment Variables

| Variable         | Description                                | Default Value      |
|------------------|--------------------------------------------|--------------------|
| `SESSION_NAME`   | The name of the session.                  | `web_session`      |
| `SESSION_TIMEOUT`| The session timeout in hours.             | `1` (1 hour)       |
| `SESSION_PATH`   | The path on the server where the session is available. | `/` |
| `SESSION_SECURE` | Whether the session should be secure (HTTPS only). | `false`            |

---

## Methods

### `start()`
Starts the session if it hasn’t already been started.

### `set(string|array $key, mixed $value = null)`
Sets a session value or multiple values if an associative array is provided.

### `get(string $key, mixed $default = null)`
Retrieves a session value by key. If the key doesn’t exist, returns the provided default value.

### `some(array $keys, mixed $default = null)`
Retrieves multiple session values by their keys. Returns an associative array with key-value pairs.

### `has(string $key)`
Checks if a session key exists.

### `remove(string $key)`
Removes a session value by key.

### `setFlash(string $key, mixed $value)`
Sets a flash session value. Flash data will persist for one session request.

### `getFlash(string $key, mixed $default = null)`
Retrieves and removes a flash session value.

### `clear()`
Clears all session values.

### `destroy()`
Destroys the session.

---

## Example with Flash Data

Flash data allows you to store session data that only persists for a single request. This is useful for storing messages that need to be displayed once, such as success or error messages.

```php
use RF\Session\Session;

// Initialize the session
$session = new Session();

// Set flash data
$session->setFlash('success', 'Your account has been created successfully!');

// Retrieve and remove flash data
echo $session->getFlash('success'); // Outputs: Your account has been created successfully!
```

---

## Notes

- **Session Timeout**: The session will automatically expire after the specified timeout duration, and any session data will be cleared.
- **Secure Cookies**: If you enable the `SESSION_SECURE` option, make sure to use HTTPS in your environment to ensure session data is transmitted securely.
- **Headers Sent**: Ensure that session management functions are called before any output is sent to the browser to avoid `headers already sent` errors.

---

## License

This library is released under the [MIT License](LICENSE).