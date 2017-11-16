DROP DATABASE cruelty_db;
CREATE DATABASE cruelty_db;
USE cruelty_db;

CREATE TABLE users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(64),
	email VARCHAR(255),
	password VARCHAR(255),
	activation_string VARCHAR(255),
	enabled BOOLEAN DEFAULT 0,
	score INT UNSIGNED DEFAULT 0,
	api_key VARCHAR(255),
	receive_emails BOOLEAN,
	donation INT UNSIGNED DEFAULT 0
);

CREATE TABLE games (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	start_time DATETIME,
	end_time DATETIME,
	total_plays INT UNSIGNED DEFAULT 0,
	total_checked INT UNSIGNED DEFAULT 0,
	ratio DECIMAL(5,3),
	complete BOOLEAN DEFAULT 0
);

CREATE TABLE games_users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT UNSIGNED,
	game_id INT UNSIGNED,
	checked_box BOOLEAN,

	FOREIGN KEY user_key (user_id) REFERENCES users (id),
	FOREIGN KEY game_key (game_id) REFERENCES games (id)
);
