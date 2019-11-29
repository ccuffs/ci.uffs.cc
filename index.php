<?php

$exclude = array('.', '..', 'css', 'fonts');
$branch_style = array(
    'dev' => 'success',
    'master' => 'info'
);

$systems = array();
$stats = array(
    'commits_total' => 0,
    'commits_today' => 0,
    'commits_week' => 0,
    'systems' => 0
);

foreach(glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
    $item = pathinfo($dir);
    $name = $item['filename'];

    if(in_array($name, $exclude)) {
        continue;
    }

    $stats['systems']++;
    $systems[$name] = array('name' => $name, 'branches' => array(), 'commits' => array());

    foreach(glob($dir . '/*', GLOB_ONLYDIR) as $branch) {
        $branch_parts = pathinfo($branch);
        $branch_name = $branch_parts['filename'];

        $systems[$name]['branches'][] = $branch_name;

        foreach(glob($branch . '/*', GLOB_ONLYDIR) as $commit) {
            $commit_parts = pathinfo($commit);

            $systems[$name]['commits'][] = array(
                'branch' => $branch_name,
                'dir' => $commit,
                'url' => $name . '/' . $branch_name . '/' . $commit_parts['filename'],
                'sha' => $commit_parts['filename'],
                'time' => filectime($commit),
            );

            $stats['commits_total']++;
        }

        usort($systems[$name]['commits'], function($a, $b) {
            return $a['time'] < $b['time'];
        });

        $yesterday = strtotime('-1 day');
        $last_week = strtotime('-1 week');

        foreach($systems[$name]['commits'] as $commit) {
            if($commit['time'] >= $yesterday) {
                $stats['commits_today']++;
            }

            if($commit['time'] >= $last_week) {
                $stats['commits_week']++;
            }
        }
    }
}
?>
<html>
<head>
    <title>CI - Ciência da Computação - UFFS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/froala_blocks.min.css">
    <link rel="stylesheet" type="text/css" href="./css/app.css">
</head>

<body id="top">
    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-md no-gutters">
                <div class="col-2 text-left">
                    <i class="fa fa-warning"></i> ci.uffs.cc
                </div>

                <div class="collapse navbar-collapse justify-content-center col-8" id="navbarNav4">
                    <ul class="navbar-nav justify-content-center">
                        <li class="nav-item active">
                            <a class="nav-link" href="https://www.froala.com">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav col-2 justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.froala.com"><i class="fa fa-github"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="panel">
                    <div class="panel-body">
                        <h3><i class="fa fa-warning"></i> Metrics </h3>
                        <hr>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat.
                        </p>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <small>Today</small><br />
                                <strong>20.13 <i class="fa fa-user"></i></strong>
                            </div>
                            <div class="col-3">
                                <small>Last</small><br />
                                <strong>20.73 <i class="fa fa-user"></i></strong>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <small>Last</small><br />
                                <strong>20.53 <i class="fa fa-user"></i></strong>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <small>Something</small><br />
                                <strong>20.24 <i class="fa fa-user"></i></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            foreach($systems as $name => $system) {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        <h2><?php echo $name; ?></h2>
                    </div>
                    <div class="panel-body">
                        <table id="tableServices-<?php echo $name; ?>" class="table table-striped table-hover table-responsive-sm no-footer"
                            role="grid">
                            <thead>
                                <tr role="row">
                                    <th>Date</th>
                                    <th>Commit</th>
                            </thead>

                            <tbody>
                                <?php foreach($system['commits'] as $commit) { ?>
                                    <tr role="row" class="odd">
                                        <td><?php echo date('Y-m-d H:i:s', $commit['time']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo isset($branch_style[$commit['branch']]) ? $branch_style[$commit['branch']] : 'warning'; ?>"><?php echo $commit['branch']; ?></span>
                                            <code><a href="./<?php echo $commit['url']; ?>" target="_blank"><?php echo $commit['sha']; ?></a></code>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
    </div>

    <footer class="fdb-block footer-large bg-dark">
        <div class="container">
            <div class="row align-items-top text-center text-md-left">
                <div class="col-12 col-sm-6 col-md-4">
                    <h3>Country A</h3>
                    <p>Street Address 52<br>Contact Name</p>
                    <p>+44 827 312 5002</p>
                    <p><a href="https://www.froala.com">countrya@amazing.com</a></p>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mt-4 mt-sm-0">
                    <h3>Country B</h3>
                    <p>Street Address 100<br>Contact Name</p>
                    <p>+13 827 312 5002</p>
                    <p><a href="https://www.froala.com">countryb@amazing.com</a></p>
                </div>

                <div class="col-12 col-md-4 mt-5 mt-md-0 text-md-left">
                    <h3>About Us</h3>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                        live the blind texts.</p>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <p class="muted">© 2019 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>