CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME,
  title CHAR(255),
  description TEXT(2048),
  picture CHAR(255) DEFAULT 'img/logo.svg',
  starting_price INT,
  ending_date DATETIME,
  bid_step INT,
  fav_count INT,
  author_id INT,
  winner_id INT,
  category_id INT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_time DATETIME,
  username CHAR(255),
  email CHAR(128),
  password CHAR(32),
  avatar CHAR(255),
  contact_info TEXT(1024)
);

CREATE TABLE bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  placement_date DATETIME,
  bid_amount INT,
  user_id INT,
  lot_id INT
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128)
);


CREATE UNIQUE INDEX mails ON users(email);
CREATE UNIQUE INDEX usernames ON users(username);

CREATE INDEX lots_titles ON lots(title);
CREATE INDEX categories_titles ON categories(title);
CREATE INDEX all_lots ON lots(id);
CREATE INDEX all_bids ON bids(id);