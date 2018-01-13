<?php

namespace App;

use App\Database\Manager;
use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;
use PDO;

class Router
{
    /**
     * @var \Klein\Klein
     */
    private $klein;

    /**
     * Router constructor.
     * @param \Klein\Klein $klein
     */
    public function __construct(\Klein\Klein $klein)
    {
        $this->klein = $klein;
    }

    public function routes()
    {
        $klein = $this->klein;
        $klein->respond(function ($request, Response $response, ServiceProvider $service, $app) {
            $service->startSession();
            $service->escape = function ($str) {
                return htmlentities($str);
            };
            $app->register('manager', function () {
                return new Manager('storage/campus.db');
            });
            $app->register('db', function () use ($app) {
                return $app->manager->getPDO();
            });

            if (isset($_SESSION['user_id'])) {
                $stmt = $app->db->prepare("SELECT * FROM users WHERE user_id=? LIMIT 1");
                $stmt->execute([$_SESSION['user_id']]);
                if ($row = $stmt->fetch()) {
                    $service->user = (object)$row;
                }
            }
        });

        $klein->respond('GET', '/login', function ($request, $response, ServiceProvider $service, $app) {
            $service->render('views/login.php');
        });

        $klein->respond('GET', '/logout', function ($request, $response, ServiceProvider $service, $app) {
            unset($_SESSION["user_id"]);
            $response->redirect('/login');
        });

        $klein->respond('GET', '/reset', function ($request, $response, ServiceProvider $service, $app) {
            $app->manager->destroy();
            $app->manager->createTables();
            $app->manager->seed();
            $service->render('views/reset.php');
        });

        $klein->respond('POST', '/login', function ($request, $response, ServiceProvider $service, $app) {
            $stmt = $app->db->prepare("SELECT * FROM users WHERE username=? and password=? LIMIT 1");
            $stmt->execute([$request->param('username'), $request->param('password')]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['user_id'] = $row['user_id'];
                $response->redirect('/');
            } else {
                $response->redirect('/login');
            }
        });

        $klein->respond('GET', '/', function ($request, $response, ServiceProvider $service, $app) {
            if (isset($service->user)) {
                $service->layout('views/layout.php');
                $service->render('views/index.php');
            } else {
                $response->redirect("/login");
            }
        });
        $klein->respond(['POST', 'GET'], '/staff', function (Request $request, $response, ServiceProvider $service, $app) {
            if (isset($service->user)) {
                $service->layout('views/layout.php');
                $search = $request->param('search');
                /// %' UNION ALL select 0, user_id, username, password, is_admin from users  --
                $stmt = $app->db->prepare("SELECT * FROM staff where name like '%" . $search . "%' or surname like '%" . $search . "%'");
                $stmt->execute();
                $service->staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $service->render('views/staff.php');
            } else {
                $response->redirect("/login");
            }
        });

    }
}