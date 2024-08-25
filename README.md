# RF\Session Library

RF\Session is a PHP library for managing sessions with enhanced features such as timeouts, flash messages, and easy session handling.

## Installation

You can install this library using Composer. Run the following command:

```bash
composer require refkinscallv/session
```

## Usage

1. **Initialize the Session**

   ```php
   use RF\Session\Session;

   $session = new Session(['timeout' => 3600]);
   ```

2. **Start a Session**

   ```php
   $session->start();
   ```

3. **Set Session Data**

   ```php
   $session->set('username', 'JohnDoe');
   $session->set(['role' => 'admin', 'loggedIn' => true]);
   ```

4. **Get Session Data**

   ```php
   $username = $session->get('username', 'defaultUser');
   ```

5. **Check if Data Exists**

   ```php
   $exists = $session->has('username');
   ```

6. **Remove Session Data**

   ```php
   $session->remove('username');
   ```

7. **Set Flash Data**

   ```php
   $session->setFlash('message', 'Welcome back!');
   ```

8. **Get Flash Data**

   ```php
   $message = $session->getFlash('message', 'No message');
   ```

9. **Clear All Session Data**

   ```php
   $session->clear();
   ```

10. **Destroy the Session**

    ```php
    $session->destroy();
    ```

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.