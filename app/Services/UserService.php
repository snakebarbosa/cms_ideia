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
        try {
            return User::all();
        } catch (\Exception $e) {
            \Log::error('Error fetching all users: ' . $e->getMessage());
            throw new \Exception('Unable to fetch users');
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        try {
            return User::find($id);
        } catch (\Exception $e) {
            \Log::error("Error fetching user {$id}: " . $e->getMessage());
            throw new \Exception('Unable to fetch user');
        }
    }

    /**
     * Get all roles
     */
    public function getAllRoles()
    {
        try {
            return Role::all();
        } catch (\Exception $e) {
            \Log::error('Error fetching all roles: ' . $e->getMessage());
            throw new \Exception('Unable to fetch roles');
        }
    }

    /**
     * Create a new user
     */
    public function createUser(array $data)
    {
        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);

            if ($user->save()) {
                $this->syncRoles($user, $data['roles'] ?? []);
                return $user;
            }

            throw new \Exception('Failed to save user');
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update user
     */
    public function updateUser($id, array $data)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                throw new \Exception('User not found');
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

            throw new \Exception('Failed to update user');
        } catch (\Exception $e) {
            \Log::error("Error updating user {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync user roles
     */
    public function syncRoles(User $user, array $roleIds)
    {
        try {
            $user->roles()->detach();
            
            foreach ($roleIds as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->attachRole($role);
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error syncing roles for user {$user->id}: " . $e->getMessage());
            throw new \Exception('Failed to sync user roles');
        }
    }

    /**
     * Verify user password
     */
    public function verifyPassword($userId, $password)
    {
        try {
            $hashedPassword = User::where('id', $userId)->value('password');
            return Hash::check($password, $hashedPassword);
        } catch (\Exception $e) {
            \Log::error("Error verifying password for user {$userId}: " . $e->getMessage());
            throw new \Exception('Failed to verify password');
        }
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
        try {
            $user = User::find($id);
            
            if (!$user) {
                throw new \Exception('User not found');
            }
            
            $user->ativado = $status;
            
            if (!$user->save()) {
                throw new \Exception('Failed to update user status');
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Error updating status for user {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bulk update user status
     */
    public function bulkUpdateStatus(array $userIds, $status)
    {
        try {
            return User::whereIn('id', $userIds)->update(['ativado' => $status]);
        } catch (\Exception $e) {
            \Log::error('Error bulk updating user status: ' . $e->getMessage());
            throw new \Exception('Failed to update user status');
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                throw new \Exception('User not found');
            }
            
            if (!$user->delete()) {
                throw new \Exception('Failed to delete user');
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Error deleting user {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds)
    {
        try {
            return User::whereIn('id', $userIds)->delete();
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting users: ' . $e->getMessage());
            throw new \Exception('Failed to delete users');
        }
    }

    /**
     * Reset user password to default
     */
    public function resetPassword($id, $defaultPassword = null)
    {
        try {
            $password = $defaultPassword ?? config('custom.DEFAULT_RESET_PASSWORD', 'password');
            
            $updated = User::where('id', $id)
                ->update(['password' => Hash::make($password)]);
            
            if (!$updated) {
                throw new \Exception('Failed to reset password');
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Error resetting password for user {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get user roles
     */
    public function getUserRoles($userId)
    {
        try {
            $user = User::find($userId);
            return $user ? $user->roles : collect();
        } catch (\Exception $e) {
            \Log::error("Error fetching roles for user {$userId}: " . $e->getMessage());
            throw new \Exception('Unable to fetch user roles');
        }
    }

    /**
     * Update user password
     */
    public function updatePassword($userId, $currentPassword, $newPassword)
    {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                throw new \Exception('User not found');
            }

            // Verify current password
            if (!Hash::check($currentPassword, $user->password)) {
                throw new \Exception('Current password is incorrect');
            }

            // Update to new password
            $user->password = Hash::make($newPassword);
            
            if (!$user->save()) {
                throw new \Exception('Failed to update password');
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error updating password for user {$userId}: " . $e->getMessage());
            throw $e;
        }
    }
}
