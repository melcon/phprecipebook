CREATE SEQUENCE security_group_id_seq;
CREATE SEQUENCE recipe_recipe_id_seq;
CREATE SEQUENCE recipe_ingredient_id_seq;
CREATE SEQUENCE recipe_list_names_seq;
CREATE SEQUENCE recipe_ethnicity_id_seq;
CREATE SEQUENCE recipe_meal_id_seq;
CREATE SEQUENCE recipe_list_id_seq;
CREATE SEQUENCE recipe_difficult_id_seq;
CREATE SEQUENCE recipe_course_id_seq;
CREATE SEQUENCE recipe_time_id_seq;
CREATE SEQUENCE recipe_base_id_seq;
CREATE SEQUENCE	recipe_location_id_seq;
CREATE SEQUENCE recipe_ethnic_id_seq;
CREATE SEQUENCE recipe_restaurant_id_seq;
CREATE SEQUENCE recipe_price_id_seq;
CREATE SEQUENCE recipe_store_id_seq;
CREATE SEQUENCE recipe_source_id_seq;

CREATE TABLE security_groups (
	group_id INTEGER DEFAULT nextval('security_group_id_seq'),
	group_name VARCHAR(64) NOT NULL UNIQUE,
	PRIMARY KEY (group_id)
);

CREATE TABLE security_users (
	user_login VARCHAR(32) NOT NULL UNIQUE,
	user_password VARCHAR(64) NOT NULL DEFAULT '',
	user_name VARCHAR(64) NOT NULL DEFAULT '',
	user_access_level INTEGER NOT NULL DEFAULT '0',
	user_language VARCHAR(8) DEFAULT 'en' NOT NULL,
	user_country VARCHAR(8) DEFAULT 'us' NOT NULL,
	user_date_created DATE DEFAULT current_date,
	user_last_login DATE,
	user_email VARCHAR(80) NOT NULL UNIQUE,
	PRIMARY KEY (user_login)
);
	
CREATE TABLE security_members (
	member_group INTEGER REFERENCES security_groups(group_id) ON DELETE CASCADE,
	member_login VARCHAR(32) REFERENCES security_users(user_login) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (member_group, member_login)
);
	
CREATE TABLE recipe_settings ( 
	setting_version REAL,
	setting_mp_day INTEGER
);

CREATE TABLE recipe_stores ( 
	store_id INTEGER DEFAULT nextval('recipe_store_id_seq'),
	store_name VARCHAR(32) NOT NULL DEFAULT '',
	store_layout TEXT,
	store_owner VARCHAR(32) DEFAULT '' REFERENCES security_users(user_login) ON DELETE SET DEFAULT ON UPDATE CASCADE,
	PRIMARY KEY (store_id)
);

CREATE TABLE recipe_ethnicity (
	ethnic_id INTEGER DEFAULT nextval('recipe_ethnic_id_seq'),
	ethnic_desc CHAR(64) NOT NULL UNIQUE,
	PRIMARY KEY (ethnic_id)
);
	
CREATE TABLE recipe_units (
	unit_id INTEGER,
	unit_desc VARCHAR(64) NOT NULL,
	unit_abbr VARCHAR(8) NOT NULL UNIQUE,
	PRIMARY KEY (unit_id)
);

CREATE TABLE recipe_locations (
	location_id INTEGER DEFAULT nextval('recipe_location_id_seq'),
	location_desc VARCHAR(64) NOT NULL UNIQUE,
	PRIMARY KEY (location_id)
);
	
CREATE TABLE recipe_bases (
	base_id INTEGER DEFAULT nextval('recipe_base_id_seq'),
	base_desc VARCHAR(64),
	PRIMARY KEY (base_id)
);

CREATE TABLE recipe_prep_time (
	time_id INTEGER DEFAULT nextval('recipe_time_id_seq'),
	time_desc VARCHAR(64),
	PRIMARY KEY (time_id)
);

CREATE TABLE recipe_courses (
	course_id INTEGER DEFAULT nextval('recipe_course_id_seq'),
	course_desc VARCHAR(64),
	PRIMARY KEY (course_id)
);
	
CREATE TABLE recipe_difficulty (
	difficult_id INTEGER DEFAULT nextval('recipe_difficult_id_seq'),
	difficult_desc VARCHAR(64),
	PRIMARY KEY (difficult_id)
);
	
CREATE TABLE recipe_ingredients (
	ingredient_id INTEGER DEFAULT nextval('recipe_ingredient_id_seq'),
	ingredient_name VARCHAR(120) NOT NULL UNIQUE,
	ingredient_desc TEXT,
	ingredient_location INTEGER REFERENCES recipe_locations(location_id) ON DELETE SET NULL,
	ingredient_price REAL,
	ingredient_unit INTEGER REFERENCES recipe_units(unit_id) ON DELETE SET NULL,
	ingredient_solid BOOLEAN,
	ingredient_system VARCHAR(8) DEFAULT 'usa',
	PRIMARY KEY (ingredient_id)
);

CREATE TABLE recipe_sources (
	source_id INTEGER DEFAULT nextval('recipe_source_id_seq'),
	source_title VARCHAR(64),
	source_desc TEXT,
	PRIMARY KEY (source_id)
);

CREATE TABLE recipe_recipes (
	recipe_id INTEGER DEFAULT nextval('recipe_recipe_id_seq'),
	recipe_name VARCHAR(128) NOT NULL UNIQUE,
	recipe_ethnic INTEGER REFERENCES recipe_ethnicity(ethnic_id) ON DELETE SET NULL,
	recipe_base INTEGER REFERENCES recipe_bases(base_id) ON DELETE SET NULL,
	recipe_course INTEGER REFERENCES recipe_courses(course_id) ON DELETE SET NULL,
	recipe_prep_time INTEGER REFERENCES recipe_prep_time(time_id) ON DELETE SET NULL,
	recipe_difficulty INTEGER REFERENCES recipe_difficulty(difficult_id) ON DELETE SET NULL,
	recipe_serving_size INTEGER,
	recipe_directions TEXT,
	recipe_comments TEXT,
	recipe_source INTEGER REFERENCES recipe_sources(source_id) ON DELETE SET NULL,
	recipe_source_desc VARCHAR(200),
	recipe_cost REAL,
	recipe_modified DATE DEFAULT current_date,
	recipe_picture OID,
	recipe_picture_type VARCHAR(32),
	recipe_private BOOLEAN NOT NULL,
	recipe_system VARCHAR(16) DEFAULT 'usa' NOT NULL,
	recipe_owner VARCHAR(32) DEFAULT '' REFERENCES security_users(user_login) ON DELETE SET DEFAULT ON UPDATE CASCADE,
	PRIMARY KEY (recipe_id)
);

CREATE TABLE recipe_ingredient_mapping (
	map_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	map_ingredient INTEGER REFERENCES recipe_ingredients(ingredient_id) ON DELETE CASCADE,
	map_quantity REAL NOT NULL,
	map_unit INTEGER REFERENCES recipe_units(unit_id) ON DELETE SET NULL,
	map_qualifier VARCHAR(32),
	map_optional BOOLEAN,
	map_order INTEGER NOT NULL,
	PRIMARY KEY (map_ingredient,map_recipe)
);
	
CREATE TABLE recipe_list_names (
	list_id INTEGER DEFAULT nextval('recipe_list_id_seq'),
	list_name VARCHAR(64),
	list_owner VARCHAR(32) REFERENCES security_users(user_login) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (list_id)
);
	
CREATE TABLE recipe_list_recipes (
	list_rp_id INTEGER REFERENCES recipe_list_names(list_id) ON DELETE CASCADE,
	list_rp_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	list_rp_scale REAL DEFAULT 0.0,
	PRIMARY KEY (list_rp_id,list_rp_recipe)
);
	
CREATE TABLE recipe_list_ingredients (
	list_ing_id INTEGER REFERENCES recipe_list_names(list_id) ON DELETE CASCADE,
	list_ing_ingredient INTEGER REFERENCES recipe_ingredients(ingredient_id) ON DELETE CASCADE,
	list_ing_unit INTEGER REFERENCES recipe_units(unit_id) ON DELETE SET NULL,
	list_ing_qualifier VARCHAR(32),
	list_ing_quantity REAL NOT NULL,
	PRIMARY KEY (list_ing_id,list_ing_ingredient)
);
	
CREATE TABLE recipe_related_recipes (
	related_parent INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	related_child INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	related_required BOOLEAN,
	PRIMARY KEY (related_parent,related_child)
);
	
CREATE TABLE recipe_favorites (
	favorite_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	favorite_owner VARCHAR(32) REFERENCES security_users(user_login) ON DELETE CASCADE ON UPDATE CASCADE,
	PRIMARY KEY (favorite_recipe,favorite_owner)
);
	
CREATE TABLE recipe_meals (
  	meal_id INTEGER DEFAULT nextval('recipe_meal_id_seq'),
	meal_name VARCHAR(64) NOT NULL UNIQUE,
	PRIMARY KEY (meal_id)
);

CREATE TABLE recipe_mealplans (
	mplan_date DATE NOT NULL DEFAULT current_date,
	mplan_meal INTEGER REFERENCES recipe_meals(meal_id) ON DELETE CASCADE,
	mplan_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	mplan_owner VARCHAR(32) REFERENCES security_users(user_login) ON DELETE CASCADE ON UPDATE CASCADE,
	mplan_servings INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY (mplan_date,mplan_meal,mplan_recipe,mplan_owner)
);
	
CREATE TABLE recipe_reviews (
	review_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	review_comments TEXT NOT NULL,
	review_date TIMESTAMP DEFAULT now(),
	review_owner VARCHAR(32) REFERENCES security_users(user_login) ON DELETE SET NULL ON UPDATE CASCADE,
	PRIMARY KEY (review_recipe,review_comments,review_owner)
);

CREATE TABLE recipe_ratings (
	rating_recipe INTEGER REFERENCES recipe_recipes(recipe_id) ON DELETE CASCADE,
	rating_score INTEGER NOT NULL DEFAULT 0,
	rating_ip VARCHAR(32) NOT NULL,
	PRIMARY KEY (rating_recipe, rating_ip)
);
	
CREATE TABLE recipe_prices (
	price_id INTEGER DEFAULT nextval('recipe_price_id_seq'),
	price_desc VARCHAR(16),
	PRIMARY KEY (price_id)
);
	
CREATE TABLE recipe_restaurants (
	restaurant_id INTEGER DEFAULT nextval('recipe_restaurant_id_seq'),
	restaurant_name VARCHAR(64) NOT NULL,
	restaurant_address VARCHAR(128),
	restaurant_city VARCHAR(32),
	restaurant_state VARCHAR(2),
	restaurant_zip VARCHAR(16),
	restaurant_country VARCHAR(64),
	restaurant_phone VARCHAR(128),
	restaurant_hours TEXT,
	restaurant_picture OID,
	restaurant_picture_type VARCHAR(64),
	restaurant_menu_text TEXT,
	restaurant_comments TEXT,
	restaurant_price INTEGER REFERENCES recipe_prices(price_id) ON DELETE SET NULL,
	restaurant_delivery BOOLEAN,
	restaurant_carry_out BOOLEAN,
	restaurant_dine_in BOOLEAN,
	restaurant_credit BOOLEAN,
	restaurant_website VARCHAR(254),
	PRIMARY KEY (restaurant_id)
);


INSERT INTO recipe_settings (setting_version,setting_mp_day) VALUES(2.33,0);
INSERT INTO security_users (user_login,user_password,user_name,user_access_level,user_country,user_email) VALUES ('admin', '76a2173be6393254e72ffa4d6df1030a', 'Administrator', '99','us','user@localhost');
INSERT INTO recipe_stores (store_name, store_layout, store_owner) VALUES('default', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43','admin');
