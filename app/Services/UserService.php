<?php

namespace App\Services;

use App\User;
use App\Role;
use Hash;

class UserService
{
    /**
     * Get all users
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return User::find($id);
    }

    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Create a new user
     */
    public function createUser(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        if ($user->save()) {
            $this->syncRoles($user, $data['roles'] ?? []);
            return $user;
        }

        return null;
    }

    /**
     * Update user
     */
    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        
        if (!$user) {
            return null;
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($user->save()) {
            if (isset($data['roles'])) {
                $this->syncRoles($user, $data['roles']);
            }
            return $user;
        }

        return null;
    }

    /**
     * Sync user roles
     */
    public function syncRoles(User $user, array $roleIds)
    {
        $user->roles()->detach();
        
        foreach ($roleIds as $roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $user->attachRole($role);
            }
        }
    }

    /**
     * Verify user password
     */
    public function verifyPassword($userId, $password)
    {
        $hashedPassword = User::where('id', $userId)->value('password');
        return Hash::check($password, $hashedPassword);
    }

    /**
     * Generate random password
     */
    public function generateRandomPassword($length = 10)
    {
        $keyspace = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        
        return $str;
    }

    /**
     * Update user status (ativado)
     */
    public function updateUserStatus($id, $status)
    {
        $user = User::find($id);
        
        if ($user) {
            $user->ativado = $status;
            return $user->save();
        }
        
        return false;
    }

    /**
     * Bulk update user status
     */
    public function bulkUpdateStatus(array $userIds, $status)
    {
        return User::whereIn('id', $userIds)->update(['ativado' => $status]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::find($id);
        
        if ($user) {
            return $user->delete();
        }
        
        return false;
    }

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds)
    {
        return User::whereIn('id', $userIds)->delete();
    }

    /**
     * Reset user password to default
     */
    public function resetPassword($id, $defaultPassword = null)
    {
        $password = $defaultPassword ?? config('custom.DEFAULT_RESET_PASSWORD', 'password');
        
        return User::where('id', $id)
            ->update(['password' => Hash::make($password)]);
    }

    /**
     * Get user roles
     */
    public function getUserRoles($userId)
    {
        $user = User::find($userId);
        return $user ? $user->roles : collect();
    }
}
