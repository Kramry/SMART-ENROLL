<?php

class Upgrade_users_for_smartenroll {

    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
        $this->_lava->call->dbforge();
    }

    public function up()
    {
        if (!$this->_lava->dbforge->table_exists('users')) {
            throw new RuntimeException('Run the initial users migration before upgrading SmartEnroll users.');
        }

        $columns = [
            'student_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => TRUE,
                'default'    => NULL,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => FALSE,
                'default'    => '',
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => FALSE,
                'default'    => '',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => TRUE,
                'default'    => NULL,
            ],
            'birth_date' => [
                'type'    => 'DATE',
                'null'    => TRUE,
                'default' => NULL,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => "'male','female','prefer_not_to_say'",
                'null'       => TRUE,
                'default'    => NULL,
            ],
            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => TRUE,
                'default' => NULL,
            ],
            'last_login_at' => [
                'type'    => 'DATETIME',
                'null'    => TRUE,
                'default' => NULL,
            ],
        ];

        foreach ($columns as $name => $definition) {
            if (!$this->_lava->dbforge->column_exists('users', $name)) {
                $this->_lava->dbforge->add_column('users', [$name => $definition]);
            }
        }

        $this->_lava->dbforge->modify_column('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => "'admin','moderator','user','student','teacher'",
                'null'       => FALSE,
                'default'    => 'student',
            ],
        ]);

        $this->_lava->dbforge->modify_column('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => "'student','teacher','admin','moderator','user'",
                'null'       => FALSE,
                'default'    => 'student',
            ],
        ]);

        $index_exists = $this->_lava->db->raw(
            'SELECT COUNT(*) FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = :table AND index_name = :index_name',
            ['table' => 'users', 'index_name' => 'users_student_number_unique']
        );

        if ((int) $index_exists->fetchColumn() === 0) {
            $this->_lava->db->raw('ALTER TABLE `users` ADD UNIQUE KEY `users_student_number_unique` (`student_number`)');
        }
    }

    public function down()
    {
        // Preserve account data and schema for safe rollback.
    }
}
