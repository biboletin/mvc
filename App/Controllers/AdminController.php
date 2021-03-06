<?php

namespace App\Controllers;

use App\Models\Users;
use Core\Controller;
use Core\Request;

/**
 * Class AdminController
 *
 * @package App\Controllers
 */
class AdminController extends Controller
{
    /**
     * Login page
     * @return string
     */
    public function login(): string
    {
        return $this->view->set('admin.login');
    }

    /**
     * User login authorization
     *
     * @param Request $request
     */
    public function auth(Request $request): void
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $users = Users::where('name', '=', $username)->get();

        if ($this->hash->verify($password, $users[0]->password)) {
            $this->session->set('loggedIn', true);
            $request->redirect('admin/dashboard');
        }
        if (! $this->hash->verify($password, $users[0]->password)) {
            echo 'logged out';

            $this->redirectToLogin();
        }
    }

    /**
     * Registration view
     * @return string
     */
    public function register(): string
    {
        return $this->view->set('admin.register');
    }

    /**
     * Insert new user
     * @param Request $request
     */
    public function registerUser(Request $request): void
    {
        $user = new UserModel();

        $user->createNewUser(
            $request->post('username'),
            $request->post('email'),
            $this->hash->hashit($request->post('password'))
        );
        $this->session->set('loggedIn', true);
        $request->redirect('admin/dashboard');
    }

    /**
     * Dashboard view
     *
     * @return string
     */
    public function dashboard(): string
    {
        if (! $this->session->get('loggedIn')) {
            $this->redirectToLogin();
        }
        return $this->view->set('admin.dashboard');
    }

    /**
     * About page
     *
     * @return string
     */
    public function about(): string
    {
        $about = new AboutModel();
        $info = $about->getAboutInfo();

        return $this->view->set('admin.about.index', $info);
    }

    /**
     * Categories page
     *
     * @return string
     */
    public function categories(): string
    {
        return $this->view->set('admin.categories.index');
    }

    public function users(Request $request): string
    {
        // error_log(print_r($request, true));
        error_log(print_r($request->all(), true));
        error_log($request->post('age'));
        error_log($request->post('id'));
        $user = [
            'id' => $request->post('age'),
            'name' => 'Mihail',
        ];
        return $this->view->set('admin.users', $user);
    }

    public function listUsers($id): string
    {
        $user = [
            'id' => $id,
            'name' => 'Mihail',
        ];
        return $this->view->set('admin.users', $user);
    }

    public function logout(): void
    {
        $this->session->close();
        $this->redirectToLogin();
    }
}
