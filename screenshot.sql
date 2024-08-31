CREATE TABLE screenshots ( id INT AUTO_INCREMENT PRIMARY KEY, 
user_id INT NOT NULL,
 views INT NOT NULL, 
 photo_path VARCHAR(255) NOT NULL, 
 reward DECIMAL(10, 2) DEFAULT 0, 
status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) );