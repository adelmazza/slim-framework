<?php

namespace App\Controllers;

use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Tracy\Debugger;
use Illuminate\Database\Capsule\Manager as DB;

final class Home extends Controller
{

    public function getHome(Request $request, Response $response, $args)
    {
        return $this->view->render($response,'home.twig', []);

    }

    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response,'login.twig', []);
    }

    public function postLogin(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        try {
            $this->auth->attempt($input['LoginEmail'], $input['LoginPassword']);

        } catch (\Exception $ex)
        {
            $this->flash->addMessage('error', $ex->getMessage());
            return $response->withRedirect($this->router->pathFor('login'));
        }

        return $response->withRedirect($this->router->pathFor('admin'));
    }

    public function getAdmin(Request $request, Response $response, $args)
    {
        return $this->view->render($response,'adduser.twig', []);
    }

    public function postAddUser(Request $request, Response $response, $args)
    {

        $this->validator->rule('required', ['UserEmail', 'UserFirstName', 'UserLastName', 'UserPassword'])->message('Required');
        $this->validator->rule('email', 'UserEmail');
        $this->validator->rule('lengthMin', 'UserPassword', 8);
        $this->validator->rule('lengthMax', 'UserPassword', 10);
        $this->validator->rule('equals', 'UserPassword', 'UserPasswordConfirm');

        $this->validator->rule(function($field, $value, $params, $fields) {
            if(User::where('UserEmail', $value)->count() > 0)
                return false;
            else
                return true;
        }, "UserEmail")->message("{field} already taken");

        if($this->validate()) {

            User::create([
                'UserFirstName' => $request->getParam('UserFirstName'),
                'UserLastName' => $request->getParam('UserLastName'),
                'UserEmail' => $request->getParam('UserEmail'),
                'UserPassword' => password_hash($request->getParam('UserPassword'), PASSWORD_DEFAULT),
                'UserIsActive' => 1,
            ]);

            $this->flash->addMessage('info', 'Utente creato con successo');

        }

        return $response->withRedirect($this->router->pathFor('admin'));
    }

    public function getLogout(Request $request, Response $response, $args)
    {
        $this->session->destroy();
        return $response->withRedirect($this->router->pathFor('home'));
    }

}