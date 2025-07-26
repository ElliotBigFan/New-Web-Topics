# Ruby Regex Whitelist Bypass Lab

This lab demonstrates a vulnerability related to how Ruby's regex engine handles multiline strings by default.

## The Vulnerability

In Ruby, the `^` anchor in a regular expression matches the beginning of a **line**, not necessarily the beginning of the entire string. This behavior can be exploited to bypass whitelists that validate URLs or other structured input.

The application in this lab uses the following regex to validate if a URL is using the `https` protocol:
```ruby
/^https/
```

While this looks secure, it can be bypassed by providing a multiline string where the `https` part is at the beginning of a subsequent line.

### Example Payload

A malicious user can provide a string like this:

```
javascript:alert('XSS')
https://anything.com
```

The newline character (`\n`) makes the Ruby regex engine see `https://anything.com` as the start of a new line. The `^https` check matches this second line, and the whole string is considered valid. If the application then renders or processes the entire input string, the initial `javascript:alert('XSS')` payload could be executed.

## How to Run This Lab

You can run this lab using either standard Docker commands or Docker Compose.

### Option 1: Using Docker Compose (Recommended)

1.  **Build and run the container:**
    From your terminal in the project's root directory, run:
    ```bash
    docker-compose up --build -d
    ```
    The `-d` flag will run it in detached mode (in the background).

2.  **Access the lab:**
    Open your web browser and navigate to `http://localhost:4567`.

3.  **Stop and remove the container:**
    ```bash
    docker-compose down
    ```

### Option 2: Using standard Docker commands

### Prerequisites
- Docker

### Steps

1.  **Build the Docker image:**
    ```bash
    docker build -t ruby-regex-lab .
    ```

2.  **Run the Docker container:**
    ```bash
    docker run -p 4567:4567 -it --rm ruby-regex-lab
    ```

3.  **Access the lab:**
    Open your web browser and navigate to `http://localhost:4567`.

4.  **Test the vulnerability:**
    - Try a normal, valid URL like `https://google.com`. It will be accepted.
    - Try an invalid URL like `http://example.com`. It will be rejected.
    - Now, try the bypass payload. Copy and paste the multiline payload below into the textarea and click "Validate":
    ```
    javascript:alert('Bypassed!')
    https://example.com
    ```
    You will see that the input is considered "valid", demonstrating the bypass. 