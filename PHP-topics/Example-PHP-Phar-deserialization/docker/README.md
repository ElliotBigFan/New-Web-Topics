# Example Phar Deserialization

## Lab Purpose:
- You're building a lab to demonstrate PHP Phar Deserialization vulnerability - a dangerous attack technique in PHP.
- How it works:
    1. Vulnerability in `index.php`:
        - Class `LogFile` has a `__destruct()` method that calls `md5_file($this->filename)`
        - When the `LogFile` object is destroyed, it reads a file from the `$_GET['file']` parameter
        - Vulnerability: If a `.phar` file is passed, PHP will automatically deserialize its metadata
    2. Payload in `make_phar.php`:
        - Creates a `.phar` file with metadata containing an `Evil` object
        - Class `Evil` has a `__destruct()` method that creates an `info.php` file containing `phpinfo()`
        - When the .phar file is read by `md5_file()`, metadata gets deserialized and triggers `__destruct()`
    3. Attack Flow:

```text 
1. Attacker creates payload.phar with Evil object in metadata
2. Sends request: ?file=phar://payload.phar
3. LogFile object gets destroyed → calls __destruct()
4. md5_file() reads the .phar file → triggers deserialization
5. Evil object gets deserialized → __destruct() is called
6. info.php file is created with phpinfo() content
```

- Docker Setup:
    - PHP 7.2 with `phar.readonly=0` and `phar.require_hash=0`
    - Apache server running on port 8081
    - Volume mount for easy testing
- Learning Objectives:
    - This lab helps understand:
        - How PHP Phar files work
        - Deserialization vulnerabilities
        - Magic methods (__destruct)
        - File inclusion attacks
        - Bypassing security controls
- Attack Steps:
    - Run `php make_phar.php` in the attacker container
    - Remove any existing `info.php` file
    - Move `payload.phar` to the main directory
    - Access the vulnerable endpoint with `?file=phar://payload.phar`
    - Check for the created `info.php` file