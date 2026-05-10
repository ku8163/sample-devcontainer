#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -c "CREATE DATABASE sample_db;"

psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d sample_db -c "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users (name, email) VALUES
    ('山田 太郎', 'yamada.taro@example.com'),
    ('鈴木 花子', 'suzuki.hanako@example.com'),
    ('佐藤 次郎', 'sato.jiro@example.com');
"
