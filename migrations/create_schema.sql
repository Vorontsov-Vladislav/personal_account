-- 1. Создание таблицы менеджеров
CREATE TABLE managers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NULL
);

-- 2. Создание таблицы менеджеров
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    inn VARCHAR(20) UNIQUE NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL
);

-- 3. Создание таблицы грузов
CREATE TABLE cargos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    container VARCHAR(50) NOT NULL,
    client_id INT NOT NULL,
    manager_id INT NOT NULL,
    arrival_date DATE NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (manager_id) REFERENCES managers(id) ON DELETE CASCADE
);