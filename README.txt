1. Place folder in C:/xampp/htdocs/
2. Start XAMPP -> Start Apache and MySQL
3. Go to http://localhost/phpmyadmin -> Create DB 'blog'
4. Run this SQL:
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
5. Visit http://localhost/blog-app/register.php to start.
