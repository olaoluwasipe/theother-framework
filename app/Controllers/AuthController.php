<?php
namespace App\Controllers;

use App\Facades\Auth;
use App\Models\User;
use Core\Controller;

class AuthController extends Controller {
    public function login () {
        if(Auth::check()) redirect('/');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
        
            $user = User::where('username', $username)->first();
            
            if(!$user) json_error('User not found');
            if ($user && password_verify($password, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;

                // $redirectUrl = $_SESSION['redirect_after_login'] ?? null;

                if (isset($_SESSION['redirect_after_login'])) {
                    $redirectTo = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']); // Clear it after use
                    json_response(['status'=> 'success', 'message' => 'Login successful', 'redirect' => $redirectTo]);
                    exit;
                }
                        
                json_response(['status'=> 'success', 'message' => 'Login successful']);
            }
        
            json_response(['status' => 'error', 'message' => "Invalid credentials"]);
        }
        
        // return $this->view('auth/login');
        json_response(['status'=>'error', 'message' => "Incorrect username and password"]);
        
    }

    public function register () {
        if(Auth::check()) redirect('/');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $name = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
        
            // Validate input
            if (empty($username) || empty($name) || empty($password)) {
                json_response(['status' => 'error', 'message' => 'All fields are required']);
            }
        
            if ($password !== $confirm_password) {
                json_response(['status' => 'error', 'message' => 'Passwords do not match']);
            }
        
            // Check if username already exists
            $existing_user = User::where('username', $username)->first();
            if ($existing_user) {
                json_response(['status' => 'error', 'message' => 'Username already taken']);
            }
        
            // Create new user
            $user = new \App\Models\User();
            $user->username = $username;
            $user->name = $name;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();
        
            // Auto login after registration
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
        
            json_response(['status' => 'success', 'message' => 'Registration successful']);
        }
        
        // return $this->view('auth/register');
        json_response(['status' => 'error', 'message' => 'Invalid request method']);
        
    }

    public function logout () {
        session_destroy();
        // json_response(['status' => 'success', 'message' => 'Logout successful']);
        redirect('/');
    }
}
