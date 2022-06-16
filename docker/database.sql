DROP TABLE categories;
DROP TABLE product;
DROP TABLE basket_element;
DROP TABLE basket;

CREATE TABLE categories (
    id SERIAL PRIMARY KEY NOT NULL,
    name VARCHAR (255) NOT NULL,
    categorie_type VARCHAR (255) NOT NULL
);

INSERT INTO categories(name, categorie_type) VALUES('Bundle Server', 'server');
INSERT INTO categories(name, categorie_type) VALUES('Network', 'network');

CREATE TABLE product (
    id SERIAL PRIMARY KEY NOT NULL,
    titre TEXT UNIQUE NOT NULL,
    description TEXT,
    cost NUMERIC NOT NULL,
    quantity INTEGER NOT NULL,
    categorie_id INTEGER NOT NULL
);

INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R610', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 12, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R410', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 7, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R710 2.5"', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 5, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R710 3.5"', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 11, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R510 12 disque', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 2, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R510 8 disque', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 5, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R815', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 1, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R320', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 1, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R310', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 1, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Bundle Dell R620', '2 Cpu<br/>16Go de Ram<br/>2 disque de faible capacite (60Go min)', 3, (SELECT id FROM categories WHERE name = 'Bundle Server'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Switch juniper 48 port', '48 port gigabits', 5, (SELECT id FROM categories WHERE name = 'Network'), 30.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Switch juniper 24 port', '24 port gigabits', 10, (SELECT id FROM categories WHERE name = 'Network'), 20.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Switch juniper 12 port', '12 port gigabits', 2, (SELECT id FROM categories WHERE name = 'Network'), 10.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Switch Cisco 24 port', '24 port gigabits', 5, (SELECT id FROM categories WHERE name = 'Network'), 10.0);
INSERT INTO product(titre, description, quantity, categorie_id, cost) VALUES('Switch Cisco 12 port', '12 port megabits', 2, (SELECT id FROM categories WHERE name = 'Network'), 10.0);

CREATE TABLE basket_element (
    id SERIAL PRIMARY KEY NOT NULL,
    uuid VARCHAR (255) NOT NULL,
    product_id INTEGER NOT NULL,
    cost NUMERIC NOT NULL,
    basket_id INTEGER,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE basket (
    id SERIAL PRIMARY KEY NOT NULL,
    uuid VARCHAR (255) NOT NULL,
    email TEXT NOT NULL,
    custom TEXT NOT NULL,
    project TEXT NOT NULL,
    status VARCHAR (255) NOT NULL DEFAULT 'En validation',
    raison TEXT NOT NULL DEFAULT '',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cost NUMERIC NOT NULL DEFAULT 0.0
);