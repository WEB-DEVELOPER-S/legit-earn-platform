CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Inactive',
    payment_status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
