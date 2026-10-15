<?php

defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class User_model extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = ['student_number', 'username', 'email', 'password', 'password_hash', 'full_name', 'role', 'phone', 'birth_date', 'gender', 'is_active', 'deleted_at', 'last_login_at'];
    protected $guarded = ['id'];
    protected $timestamps = TRUE;
    protected $created_at_column = 'created_at';
    protected $updated_at_column = 'updated_at';
    protected $soft_delete_column = 'deleted_at';
    protected $has_soft_delete = TRUE;

    public function findByIdentifier($identifier)
    {
        $identifier = trim((string) $identifier);

        if ($identifier === '') {
            return null;
        }

        $query = $this->db->table($this->table)
            ->where('email', $identifier)
            ->or_where('username', $identifier)
            ->or_where('student_number', $identifier)
            ->limit(1);

        $result = $query->get();

        return is_array($result) ? (object) $result : $result;
    }

    public function findById($id)
    {
        $result = $this->db->table($this->table)->where('id', (int) $id)->limit(1)->get();

        return is_array($result) ? (object) $result : $result;
    }

    public function createUser(array $data)
    {
        $clean = $this->fillable_attributes($data);
        if (empty($clean['password_hash'])) {
            throw new InvalidArgumentException('Password hash is required.');
        }

        $clean['created_at'] = date('Y-m-d H:i:s');
        $clean['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->table($this->table)->insert($clean);
    }

    public function usernameExists($username)
    {
        return !empty($this->db->table($this->table)
            ->where('username', $username)
            ->limit(1)
            ->get());
    }

    public function emailExists($email)
    {
        return !empty($this->db->table($this->table)
            ->where('email', $email)
            ->limit(1)
            ->get());
    }

    public function createStudentAccount(array $data)
    {
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
        if ($password_hash === false) {
            throw new RuntimeException('Unable to secure the password.');
        }

        $now = date('Y-m-d H:i:s');

        return $this->db->table($this->table)->insert([
            'student_number' => null,
            'username'       => $data['username'],
            'email'          => $data['email'],
            'password'       => $password_hash,
            'password_hash'  => $password_hash,
            'full_name'      => $data['full_name'],
            'role'           => 'student',
            'is_active'      => 1,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }

    public function updateLastLogin($id)
    {
        return $this->db->table($this->table)
            ->where('id', (int) $id)
            ->update([
                'last_login_at' => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
    }
}
