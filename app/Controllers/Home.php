<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        if (session()->get('user')) {
            return 'Welcome, ' . session()->get('user')['username'] . '! <a href="' . base_url('home/logout') . '">Logout</a>';
        }
        return view('login');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $this->db->table('users')->where('username', $username)->get()->getRow();

            if ($user && password_verify($password, $user->password)) {
                session()->set('user', [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                ]);
                return redirect()->to(base_url('home'));
            } else {
                session()->setFlashdata('error', 'Invalid username or password');
                return redirect()->to(base_url('home'));
            }
        }
        return redirect()->to(base_url('home'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('home'));
    }
}
