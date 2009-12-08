CREATE SEQUENCE recipe_price_id_seq;

CREATE TABLE recipe_prices (
	price_id INTEGER DEFAULT nextval('recipe_price_id_seq'),
	price_desc VARCHAR(16),
	PRIMARY KEY (price_id));
	
CREATE TABLE recipe_restaurants (
	restaurant_name VARCHAR(64) NOT NULL,
	restaurant_cuisine VARCHAR(32),
	restaurant_address VARCHAR(128),
	restaurant_city VARCHAR(32),
	restaurant_state VARCHAR(2),
	restaurant_zip VARCHAR(5),
	restaurant_phone VARCHAR(32),
	restaurant_hours VARCHAR(32),
	restaurant_menu_pic OID,
	restaurant_menu_type VARCHAR(32),
	restaurant_menu_text TEXT,
	restaurant_comments VARCHAR(128),
	restaurant_review TEXT,
	restaurant_price INTEGER REFERENCES recipe_prices(price_id) ON DELETE SET NULL,
	restaurant_deliver BOOLEAN,
	restaurant_carry_out BOOLEAN,
	restaurant_dine_in BOOLEAN,
	restaurant_owner VARCHAR(32) REFERENCES security_users(user_login) ON DELETE CASCADE,
	PRIMARY KEY (restaurant_name, restaurant_owner));
	

INSERT INTO recipe_prices (price_desc) VALUES ('$0-$10');
INSERT INTO recipe_prices (price_desc) VALUES ('$10-$15');
INSERT INTO recipe_prices (price_desc) VALUES ('$15-$20');
INSERT INTO recipe_prices (price_desc) VALUES ('$20-$25');
INSERT INTO recipe_prices (price_desc) VALUES ('$25-$30');
INSERT INTO recipe_prices (price_desc) VALUES ('$30+');

ALTER TABLE recipe_ingredients ADD COLUMN ingredient_desc TEXT;
UPDATE recipe_settings SET setting_version='2.05';
