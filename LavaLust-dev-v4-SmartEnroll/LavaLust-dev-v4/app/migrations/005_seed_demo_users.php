<?php

class Seed_demo_users {

    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
    }

    public function up()
    {
        $demo_users = [
            [
                'student_number' => 'DEMO-2026-001',
                'username'       => 'demo.student',
                'email'          => 'student.demo@smartenroll.test',
                'password'       => 'StudentDemo2026!',
                'full_name'      => 'Jordan Reyes',
                'role'           => 'student',
            ],
            [
                'student_number' => NULL,
                'username'       => 'demo.teacher',
                'email'          => 'teacher.demo@smartenroll.test',
                'password'       => 'TeacherDemo2026!',
                'full_name'      => 'Morgan Santos',
                'role'           => 'teacher',
            ],
            [
                'student_number' => NULL,
                'username'       => 'demo.admin',
                'email'          => 'admin.demo@smartenroll.test',
                'password'       => 'AdminDemo2026!',
                'full_name'      => 'Alex Rivera',
                'role'           => 'admin',
            ],
        ];

        foreach ($demo_users as $demo_user) {
            $existing = $this->_lava->db->table('users')
                ->where('username', $demo_user['username'])
                ->limit(1)
                ->get();

            if (!empty($existing)) {
                continue;
            }

            $now = date('Y-m-d H:i:s');
            $password_hash = password_hash($demo_user['password'], PASSWORD_DEFAULT);

            $this->_lava->db->table('users')->insert([
                'student_number' => $demo_user['student_number'],
                'username'       => $demo_user['username'],
                'email'          => $demo_user['email'],
                'password'       => $password_hash,
                'password_hash'  => $password_hash,
                'full_name'      => $demo_user['full_name'],
                'role'           => $demo_user['role'],
                'is_active'      => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }

    public function down()
    {
        $now = date('Y-m-d H:i:s');

        foreach (['demo.student', 'demo.teacher', 'demo.admin'] as $username) {
            $this->_lava->db->table('users')
                ->where('username', $username)
                ->update([
                    'is_active'  => 0,
                    'deleted_at' => $now,
                    'updated_at' => $now,
                ]);
        }
    }
}
