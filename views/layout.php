<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $this->escape($this->pageTitle); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/dashboard.css" rel="stylesheet">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Campus System</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $this->request->uri() == '/' ? 'active' : '' ?>">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav mt-2 mt-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="/logout" style="color: #b95e4e">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="container-fluid">
    <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= $this->request->uri() == '/' ? 'active' : '' ?>" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Student Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->request->uri() == '/grades' ? 'active' : '' ?>" href="/grades">Grades/Points</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Course Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->request->uri() == '/staff' ? 'active' : '' ?>" href="/staff">Academic Staff</a>
                </li>
            </ul>
            <?php if($this->user->is_admin): ?>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= $this->request->uri() == '/students' ? 'active' : '' ?>" href="/students">Students</a>
                </li>
            </ul>
            <?php endif; ?>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
            <?= $this->yieldView(); ?>
        </main>
</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="/assets/js/jquery-slim.min.js"><\/script>')</script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>