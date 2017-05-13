CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_time DATETIME NOT NULL,
  username CHAR(255) UNIQUE NOT NULL,
  email CHAR(128) UNIQUE NOT NULL,
  password CHAR(32) NOT NULL,
  avatar CHAR(255),
  contact_info TEXT(1024)
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128) UNIQUE NOT NULL
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME NOT NULL,
  title CHAR(255) NOT NULL,
  description TEXT(2048),
  picture CHAR(255) DEFAULT 'img/logo.svg',
  starting_price INT NOT NULL,
  ending_date DATETIME,
  bid_step INT,
  fav_count INT DEFAULT 0,
  author_id INT NOT NULL,
  winner_id INT,
  lot_category INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id),
  FOREIGN KEY (lot_category) REFERENCES categories(id)
);

CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  placement_date DATETIME NOT NULL,
  bid_amount INT NOT NULL,
  bid_author INT NOT NULL,
  bid_lot INT NOT NULL,
  FOREIGN KEY (bid_author) REFERENCES users(id),
  FOREIGN KEY (bid_lot) REFERENCES lots(id)
);

CREATE INDEX lots_titles ON lots(title);