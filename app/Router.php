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

        $klein->respond('GET', '/grades', function ($request, $response, ServiceProvider $service, $app) {
            if (isset($service->user)) {
                if($service->user->is_admin){
                    $response->redirect('/');
                    return;
                }
                $service->mission_complete = false;

                $stmt = $app->db->prepare("SELECT * FROM lectures INNER JOIN lessons ON lessons.lesson_id = lectures.lesson_id where user_id = ?");
                $stmt->execute([$service->user->user_id]);
                $service->lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $service->layout('views/layout.php');
                $service->render('views/grades2.php');
            } else {
                $response->redirect("/login");
            }
        });

        /*
         * Try this:    xx%' UNION ALL select user_id, name, surname, username, password from users  --
         */
        $klein->respond('GET', '/students', function ($request, $response, ServiceProvider $service, $app) {
            if (isset($service->user) && $service->user->is_admin) {
                $service->layout('views/layout.php');
                $search = $request->param('search');
                $stmt = $app->db->prepare("SELECT * FROM users where is_admin = 0");
                $stmt->execute();
                $service->students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $service->render('views/students.php');
            } else {
                $response->redirect("/login");
            }
        });


        $klein->respond(['GET', 'POST'], '/student/[:student_id]/grades', function (Request $request, $response, ServiceProvider $service, $app) {
            if (isset($service->user) && $service->user->is_admin) {
                $service->mission_complete = false;
                $stmt = $app->db->prepare("SELECT * FROM users where user_id = ?");
                $stmt->execute([$request->student_id]);
                if ($service->student = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $service->update_error = false;
                    if ($request->method() == 'POST') {
                        if ($service->student['is_locked']) {
                            $service->update_error = true;
                        } else {
                            $stmt = $app->db->prepare("UPDATE lectures SET grade = ? where lesson_id = ? and user_id = ?");
                            if ($request->student_id == 1) {
                                $service->mission_complete = 1;
                            }
                            foreach ($request->param('grades') as $lesson_id => $value) {
                                $stmt->execute([$value, $lesson_id, $request->student_id]);
                            }
                        }
                    }

                    $stmt = $app->db->prepare("SELECT * FROM lectures INNER JOIN lessons ON lessons.lesson_id = lectures.lesson_id where user_id = ?");
                    $stmt->execute([$request->student_id]);
                    $service->lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $service->layout('views/layout.php');
                    $service->render('views/grades.php');
                } else {
                    $response->redirect("/students");
                }
            } else {
                $response->redirect("/login");
            }
        });

        /*
         * Try this:   ', is_locked = '0
         */
        $klein->respond(['GET', 'POST'], '/student/[:student_id]/edit', function (Request $request, $response, ServiceProvider $service, $app) {
            if (isset($service->user) && $service->user->is_admin) {

                $country = $request->param('country');
                $faculty = $request->param('faculty');
                $field = $request->param('field');
                $user_id = $request->student_id;

                if ($request->method() == 'POST') {
                    $stmt = $app->db->prepare("UPDATE users SET country = '$country', faculty = '$faculty', field = '$field' where user_id = $user_id ");
                    $stmt->execute();
                }

                $stmt = $app->db->prepare("SELECT * FROM users where user_id = ?");
                $stmt->execute([$request->student_id]);
                if ($service->student = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $service->layout('views/layout.php');
                    $service->render('views/edit.php');
                } else {
                    $response->redirect("/students");
                }
            } else {
                $response->redirect("/login");
            }
        });


        $klein->respond(['GET'], '/student/[:student_id]/lock', function (Request $request, $response, ServiceProvider $service, $app) {
            if (isset($service->user) && $service->user->is_admin) {
                $stmt = $app->db->prepare("SELECT * FROM users where user_id = ?");
                $stmt->execute([$request->student_id]);
                if ($service->student = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $stmt = $app->db->prepare("UPDATE users set is_locked = 1 where user_id = ?");
                    $stmt->execute([$service->student['user_id']]);
                    $response->redirect("/student/" . $service->student['user_id'] . '/grades');
                } else {
                    $response->redirect("/students");
                }
            } else {
                $response->redirect("/login");
            }
        });

    }
}