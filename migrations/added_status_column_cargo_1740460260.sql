ALTER TABLE cargos ADD COLUMN status ENUM('Awaiting', 'On board', 'Finished') NOT NULL DEFAULT 'Awaiting';

UPDATE cargos SET status = 'Awaiting' WHERE status IS NULL;