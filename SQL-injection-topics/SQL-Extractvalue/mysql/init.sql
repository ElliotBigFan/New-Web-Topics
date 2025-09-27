CREATE TABLE candidates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  village VARCHAR(100),
  status VARCHAR(100)
);

CREATE TABLE secret (
  id INT PRIMARY KEY AUTO_INCREMENT,
  flag VARCHAR(255)
);

INSERT INTO secret (flag) VALUES ('CBJS{sqli_insert_error_extractvalue}');
