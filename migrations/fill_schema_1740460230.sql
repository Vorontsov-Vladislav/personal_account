INSERT INTO managers (last_name, first_name, email, phone) VALUES
('Иванов', 'Алексей', 'ivanov@example.com', '+79001112233'),
('Петров', 'Виктор', 'petrov@example.com', '+79004445566'),
('Сидоров', 'Виктор', 'sidorov@example.com', NULL),
('Иванов', 'Виктор', 'ivanovv@example.com', ''),
('Тихомирова', 'Наталья', 'tihomirova@example.com', '+79004445511');

INSERT INTO clients (company_name, inn, address, email, phone) VALUES
('ООО "Логистика"', '7708123456', 'Москва, ул. Логистов, д.5', 'logist@example.com', '+74951234567'),
('АО "Грузоперевозки"', '7711123456', 'Санкт-Петербург, ул. Транспортная, д.10', 'cargo@example.com', '+78121234567');

INSERT INTO cargos (container, client_id, manager_id, arrival_date) VALUES
('CONT1234567', 1, 1, '2025-03-01'),
('CONT7654321', 2, 2, '2025-03-05'),
('CONT7654123', 1, 5, '2025-04-05'),
('CONT7654213', 2, 5, '2025-02-05');