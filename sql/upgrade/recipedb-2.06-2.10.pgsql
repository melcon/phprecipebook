CREATE SEQUENCE recipe_restaurant_id_seq;

CREATE TABLE recipe_restaurants (
	restaurant_id INTEGER DEFAULT nextval('recipe_restaurant_id_seq'),
	restaurant_name VARCHAR(64) NOT NULL,
	restaurant_address VARCHAR(128),
	restaurant_city VARCHAR(32),
	restaurant_state VARCHAR(2),
	restaurant_zip VARCHAR(16),
	restaurant_phone VARCHAR(128),
	restaurant_hours TEXT,
	restaurant_web VARCHAR(256),
	restaurant_picture OID,
	restaurant_picture_type VARCHAR(32),
	restaurant_menu_text TEXT,
	restaurant_comments TEXT,
	restaurant_price INTEGER REFERENCES recipe_prices(price_id) ON DELETE SET NULL,
	restaurant_delivery BOOLEAN,
	restaurant_carry_out BOOLEAN,
	restaurant_dine_in BOOLEAN,
	restaurant_credit BOOLEAN,
	PRIMARY KEY (restaurant_id)
);

INSERT INTO recipe_recipes (recipe_name, recipe_serving_size, recipe_private, recipe_owner) VALUES ('Restaurant', 2, 'f', 'admin');

UPDATE recipe_settings SET setting_version='2.10';
