CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(64)
);

INSERT INTO users (username, password)
VALUES ('admin', '3ac6b24dc611a69217f3e16d6b16f1a7'); -- giả sử flag hash

CREATE TABLE notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  content TEXT
);
