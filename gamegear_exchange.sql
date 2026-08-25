CREATE DATABASE IF NOT EXISTS gamegear_exchange CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gamegear_exchange;


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NULL,
    username VARCHAR(50) NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(200) NULL,
    password_hash VARCHAR(200) NULL,
    phone VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL
);


CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);


CREATE TABLE listings (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    item_condition ENUM('New','Like New','Used - Good','Used - Fair') NOT NULL DEFAULT 'Used - Good',
    image_path VARCHAR(200),
    status ENUM('Available','Sold') NOT NULL DEFAULT 'Available',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE SET NULL,
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


CREATE TABLE announcement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type CHAR(1) NOT NULL,
    posted DATETIME NOT NULL
);


CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salutation VARCHAR(10),
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(20),
    enquiry_type VARCHAR(250),
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_email VARCHAR(200) NOT NULL,
    purchased_items TEXT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart_item (user_id, item_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES listings(listing_id) ON DELETE CASCADE
);


CREATE TABLE wishlist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist_item (user_id, item_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES listings(listing_id) ON DELETE CASCADE
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


INSERT INTO listings (seller_id, category_id, title, description, price, item_condition, image_path, status, is_featured, views) VALUES
(1, 1, 'Nintendo 64 Console', 'Fully tested N64 console, deep cleaned, comes with one controller.', 250.00, 'Used - Good', 'pc.jpg', 'Available', 0, 12),
(2, 2, 'Xbox Wireless Controller', 'Slightly worn but fully functional, both thumbsticks tight.', 90.00, 'Used - Fair', 'xbox.jpg', 'Available', 0, 5),
(1, 3, 'HyperX Cloud II Headset', 'Barely used, comes with original box and mic.', 150.00, 'Like New', 'keyboard.jpg', 'Available', 1, 8),
(3, 5, 'ASUS RTX Graphics Card', 'Upgraded to a newer card, this one still runs great.', 900.00, 'Used - Good', '3070.jpg', 'Available', 1, 20),
(2, 6, 'Sealed Zelda PS1 Collector Case', 'Rare collectible, kept in display case, never opened.', 300.00, 'New', 'zeldabreathofwild.jpg', 'Sold', 0, 30),
(3, 4, 'RGB Gaming Chair', 'Comfortable, minor scuff on armrest, otherwise great condition.', 220.00, 'Used - Good', 'Header.jpg', 'Available', 0, 3);

INSERT INTO comments (listing_id, commenter_name, comment_text) VALUES
(1, 'Tioe Seng Hao', 'Is the cartridge slot still tight? Interested in buying.'),
(1, 'Lee Kang Zheng', 'Great seller, bought a controller from them before!'),
(4, 'Chai Yi Feng', 'Does this come with the original box?');
