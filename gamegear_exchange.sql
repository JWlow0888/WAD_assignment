CREATE DATABASE IF NOT EXISTS gamegear_exchange;
USE gamegear_exchange;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

CREATE TABLE listings (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    item_condition ENUM('New','Like New','Used - Good','Used - Fair') NOT NULL DEFAULT 'Used - Good',
    image_path VARCHAR(255),
    status ENUM('Available','Sold') NOT NULL DEFAULT 'Available',
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);


CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    commenter_name VARCHAR(100) NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id) ON DELETE CASCADE
);



INSERT INTO users (full_name, email, password, phone) VALUES
('Low Jun Wei', 'low@example.com', 'password123', '012-3456789'),
('Khor Boon Ping', 'khor@example.com', 'password123', '012-9876543'),
('Yap Wei Yang', 'yap@example.com', 'password123', '012-1122334');

INSERT INTO categories (category_name) VALUES
('Consoles'),
('Controllers'),
('Headsets'),
('Gaming Chairs'),
('PC Parts & Peripherals'),
('Collectibles');

INSERT INTO listings (seller_id, category_id, title, description, price, item_condition, image_path, status, views) VALUES
(1, 1, 'Nintendo 64 Console', 'Fully tested N64 console, deep cleaned, comes with one controller.', 250.00, 'Used - Good', '', 'Available', 12),
(2, 2, 'Xbox Wireless Controller', 'Slightly worn but fully functional, both thumbsticks tight.', 90.00, 'Used - Fair', '', 'Available', 5),
(1, 3, 'HyperX Cloud II Headset', 'Barely used, comes with original box and mic.', 150.00, 'Like New', '', 'Available', 8),
(3, 5, 'ASUS RTX Graphics Card', 'Upgraded to a newer card, this one still runs great.', 900.00, 'Used - Good', '', 'Available', 20),
(2, 6, 'Sealed Zelda PS1 Collector Case', 'Rare collectible, kept in display case, never opened.', 300.00, 'New', '', 'Sold', 30),
(3, 4, 'RGB Gaming Chair', 'Comfortable, minor scuff on armrest, otherwise great condition.', 220.00, 'Used - Good', '', 'Available', 3);

INSERT INTO comments (listing_id, commenter_name, comment_text) VALUES
(1, 'Tioe Seng Hao', 'Is the cartridge slot still tight? Interested in buying.'),
(1, 'Lee Kang Zheng', 'Great seller, bought a controller from them before!'),
(4, 'Chai Yi Feng', 'Does this come with the original box?');
