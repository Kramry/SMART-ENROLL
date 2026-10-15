<?php

class Create_smartenroll_users_table {

    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
        $this->_lava->call->dbforge();
    }

    public function up()
    {
        if ($this->_lava->dbforge->table_exists('users')) {
            return;
        }

        $this->_lava->dbforge
            ->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => TRUE,
                    'auto_increment' => TRUE,
                    'null'           => FALSE,
                ],
                'student_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => TRUE,
                    'unique'     => TRUE,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => FALSE,
                    'unique'     => TRUE,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => FALSE,
                    'unique'     => TRUE,
                ],
                'password_hash' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => FALSE,
                ],
                'full_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => FALSE,
                ],
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => "'student','teacher','admin'",
                    'null'       => FALSE,
                    'default'    => 'student',
                ],
                'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => TRUE,
                ],
                'birth_date' => [
                    'type'       => 'DATE',
                    'null'       => TRUE,
                ],
                'gender' => [
                    'type'       => 'ENUM',
                    'constraint' => "'male','female','prefer_not_to_say'",
                    'null'       => TRUE,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => TRUE,
                    'null'       => FALSE,
                    'default'    => 1,
                ],
                'deleted_at' => [
                    'type'       => 'DATETIME',
                    'null'       => TRUE,
                    'default'    => NULL,
                ],
                'last_login_at' => [
                    'type'       => 'DATETIME',
                    'null'       => TRUE,
                    'default'    => NULL,
                ],
                'created_at' => [
                    'type'    => 'DATETIME',
                    'null'    => FALSE,
                    'default' => 'CURRENT_TIMESTAMP',
                ],
                'updated_at' => [
                    'type'    => 'DATETIME',
                    'null'    => TRUE,
                    'default' => NULL,
                ],
            ])
            ->add_key('id', primary: TRUE)
            ->add_key('username', unique: TRUE, name: 'users_username_unique')
            ->add_key('email', name: 'users_email_idx')
            ->add_key('student_number', unique: TRUE, name: 'users_student_number_unique')
            ->add_key('role', name: 'users_role_idx')
            ->create_table('users');
    }

    public function down()
    {
        $this->_lava->dbforge->drop_table('users');
    }
}
