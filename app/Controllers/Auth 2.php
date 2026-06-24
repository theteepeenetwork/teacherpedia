<?php

namespace App\Controllers;

use App\Models\Login_model;
use App\Models\Admin_login_model;

/**
 * Auth — login / register / admin sign-in for the Teacherpedia CI4 rebuild.
 *
 * Session contract (route filters depend on these keys EXACTLY):
 *   USER login  -> ['id','first_name','second_name','email','isLoggedIn'=>true,
 *                   'admin'=>($user['admin'] ?? 'no'),'subscriber','communication']
 *   ADMIN login -> same keys, with 'admin' => 'yes'
 *   'auth'  filter requires session('id')
 *   'admin' filter requires session('id') AND session('admin')==='yes'
 *
 * CSRF is disabled globally — forms are plain POST (no csrf_field()).
 */
class Auth extends BaseController
{
    // ---------------------------------------------------------------- USER

    /** GET /login — render sign-in (with client toggle to register). */
    public function login()
    {
        if (session('id')) {
            return redirect()->to('/account');
        }

        return view('auth/login', [
            'activeNav' => 'login',
            'mode'      => 'login',
            'error'     => session()->getFlashdata('error'),
            'success'   => session()->getFlashdata('success'),
        ]);
    }

    /** POST /login — verify credentials against `users`. */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('error', 'Please enter a valid email and password.');
            return redirect()->to('/login')->withInput();
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $model = new Login_model();
        $user  = $model->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'] ?? '')) {
            session()->setFlashdata('error', 'Incorrect login details entered. Try again.');
            return redirect()->to('/login')->withInput();
        }

        $this->setUserSession($user, $user['admin'] ?? 'no');

        $redirect = session()->getFlashdata('redirect_url') ?: '/account';
        return redirect()->to($redirect);
    }

    /** POST /register — create a `users` row (model hashes the password). */
    public function attemptRegister()
    {
        $rules = [
            'full_name' => 'required|min_length[2]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]',
            'agree'     => 'required',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'An account with that email already exists. Try signing in.',
            ],
            'agree' => [
                'required' => 'You must agree to the privacy policy to create an account.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to('/login')->withInput();
        }

        $email = trim((string) $this->request->getPost('email'));

        // Split "Jane Smith" into first / second name.
        $full  = trim((string) $this->request->getPost('full_name'));
        $parts = preg_split('/\s+/', $full, 2);
        $first  = $parts[0] ?? $full;
        $second = $parts[1] ?? '';

        $model = new Login_model();

        $newData = [
            'first_name'       => $first,
            'second_name'      => $second,
            'email'            => $email,
            'username'         => $email,
            'password'         => (string) $this->request->getPost('password'),
            'subscriber'       => 'free',
            'communication'    => 'no',
            'verification_key' => uniqid('', true),
        ];

        try {
            $model->insert($newData);
        } catch (\Throwable $e) {
            // Defensive: duplicate email / DB constraint.
            session()->setFlashdata('error', 'We could not create your account. That email may already be in use.');
            return redirect()->to('/login')->withInput();
        }

        $user = $model->where('email', $email)->first();
        if ($user) {
            $this->setUserSession($user, $user['admin'] ?? 'no');
            return redirect()->to('/account');
        }

        session()->setFlashdata('success', 'Account created — please sign in.');
        return redirect()->to('/login');
    }

    /** GET /logout — destroy session and return home. */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    /** GET /verify-email/{email}/{key} — best-effort email verification. */
    public function verifyEmail($email = null, $key = null)
    {
        $model    = new Login_model();
        $verified = $model->verify_email($email, $key);

        if ($verified === 'done') {
            session()->setFlashdata('success', "Email verified! You can now sign in.");
        } elseif ($verified === 'already_verified') {
            session()->setFlashdata('success', 'Your email is already verified.');
        } else {
            session()->setFlashdata('error', 'That verification link is invalid or has expired.');
        }

        return redirect()->to('/login');
    }

    // --------------------------------------------------------------- ADMIN

    /** GET /admin/login — render admin sign-in. */
    public function adminLogin()
    {
        if (session('id') && session('admin') === 'yes') {
            return redirect()->to('/admin');
        }

        return view('auth/login', [
            'activeNav' => 'login',
            'mode'      => 'admin',
            'error'     => session()->getFlashdata('error'),
            'success'   => session()->getFlashdata('success'),
        ]);
    }

    /** POST /admin/login — verify credentials against `admin_users`. */
    public function attemptAdminLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('error', 'Please enter a valid email and password.');
            return redirect()->to('/admin/login')->withInput();
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $model = new Admin_login_model();
        $user  = $model->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'] ?? '')) {
            session()->setFlashdata('error', 'Incorrect admin login details. Try again.');
            return redirect()->to('/admin/login')->withInput();
        }

        $this->setUserSession($user, 'yes');

        return redirect()->to('/admin');
    }

    // -------------------------------------------------------------- HELPER

    /**
     * Set the exact session keys the route filters & app pages read.
     *
     * @param array  $user  row from `users` or `admin_users`
     * @param string $admin 'yes' | 'no'
     */
    private function setUserSession(array $user, string $admin): void
    {
        session()->set([
            'id'            => $user['id'],
            'first_name'    => $user['first_name'] ?? '',
            'second_name'   => $user['second_name'] ?? '',
            'email'         => $user['email'] ?? '',
            'isLoggedIn'    => true,
            'admin'         => $admin,
            'subscriber'    => $user['subscriber'] ?? 'free',
            'communication' => $user['communication'] ?? 'no',
        ]);
    }
}
