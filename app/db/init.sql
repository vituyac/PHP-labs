CREATE TABLE IF NOT EXISTS coaches (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL,
    gym VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);
