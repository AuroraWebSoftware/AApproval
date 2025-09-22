-- approval_requests
INSERT INTO approval_requests (id, model_type, model_id, changes, status, requested_by, action_type) VALUES
(1, 'App\\Models\\Post', 5, '{"title":"Yeni Başlık","content":"..." }', 'pending', 2, 'update'),
(2, 'App\\Models\\User', 10, '{"name":"Ali Veli"}', 'pending', 1, 'create'),
(3, 'App\\Models\\Post', 7, '{}', 'pending', 3, 'delete');

-- approval_steps
INSERT INTO approval_steps (id, approval_request_id, step, name, type, identifier, status) VALUES
(1, 1, 1, 'Manager Approval', 'role', '["manager"]', 'pending'),
(2, 1, 2, 'Finance Approval', 'role', '["finance"]', 'pending'),
(3, 1, 3, 'GM Approval', 'user', '[1]', 'pending'),
(4, 2, 1, 'Admin Approval', 'role', '["admin"]', 'pending'),
(5, 3, 1, 'Manager Approval', 'role', '["manager"]', 'pending');
