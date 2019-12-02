<?php
    require_once(__DIR__ . '/vendor/app/App.php');
    $app = new App(__DIR__);
?>
<html>
<head>
    <title>CI - Ciência da Computação - UFFS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="index, nofollow">

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/froala_blocks.min.css">
    <link rel="stylesheet" type="text/css" href="./css/app.css?201912012231">
</head>

<body id="top">
    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-md no-gutters">
                <div class="col-2 text-left">
                    <i class="fa fa-area-chart"></i> ci.uffs.cc
                </div>

                <div class="collapse navbar-collapse justify-content-center col-8" id="navbarNav4">
                    <ul class="navbar-nav justify-content-center">
                        <li class="nav-item"><a class="nav-link" href="https://cc.uffs.edu.br">CC</a></li>
                        <li class="nav-item"><a class="nav-link" href="http://uffs.edu.br">UFFS</a></li>
                    </ul>
                </div>

                <ul class="navbar-nav col-2 justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/ccuffs/"><i class="fa fa-github"></i> Github</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-8">
                <div class="panel">
                    <div class="panel-body">
                        <h3><i class="fa fa-bar-chart"></i> Stats </h3>
                        <hr>
                        <p>
                            Statistics presented below are gathered from CI builds. They might be different from the real amount of commits shown in the project repository. 
                        </p>
                        <hr>
                        <div class="row stats">
                            <div class="col-3">
                                <i class="fa fa-server muted" title="Number of monitored systems"></i> Projects<br />
                                <strong><?php echo $app->stats('systems'); ?></strong>
                            </div>
                            <div class="col-3">
                                <i class="fa fa-code-fork muted" title="All tracked commits"></i> Total commits<br />
                                <strong><?php echo $app->stats('commits_total'); ?></strong>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <i class="fa fa-clock-o muted" title="Commits today"></i> Daily commits<br />
                                <strong><?php echo $app->stats('commits_today'); ?></strong>
                            </div>
                            <div class="col-md-3 col-xs-6">
                                <i class="fa fa-calendar-o muted" title="Commits this week"></i> Weekly commits<br />
                                <strong><?php echo $app->stats('commits_week'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            foreach($app->getSystems() as $id => $system) {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="panel panel-filled">
                    <div class="panel-heading status-list">
                        <h2 class="float-left"><i class="fa fa-server"></i> <?php echo $id; ?><small class="muted"><?php echo $system['name']; ?></small></h2>
                        <?php if(!empty($system['repo']['url'])) { ?><a href="<?php echo $system['repo']['url']; ?>" target="_blank" title="Github repository"><i class="fa fa-github muted"></i></a><?php } ?>
                        <?php if(!empty($system['production_url'])) { ?><a href="<?php echo $system['production_url']; ?>" target="_blank" title="Production URL"><i class="fa fa-gg-circle muted"></i></a><?php } ?>
                        <div class="float-right">
                            <img src="https://img.shields.io/github/workflow/status/<?php echo $system['repo']['owner']; ?>/<?php echo $id; ?>/<?php echo $system['github_workflow_name']; ?>?label=%20&logo=github&logoColor=white&style=for-the-badge" title="Build status" />
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="tableServices-<?php echo $id; ?>" class="table table-striped table-hover table-responsive-sm no-footer"
                            role="grid">
                            <thead>
                                <tr role="row">
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 20%">Date</th>
                                    <th style="width: 50%">Commit</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($system['commits'] as $commit) { ?>
                                    <tr role="row">
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo date('Y-m-d H:i:s', $commit['time']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $app->getBranchStyle($commit['branch']); ?>"><?php echo $commit['branch']; ?></span>
                                            <code><a href="./<?php echo $commit['url']; ?>" target="_blank"><?php echo $commit['sha']; ?></a></code>
                                        </td>
                                        <td class="text-right">
                                            <?php if($commit['audit'] != '') { ?> <a href="./<?php echo $commit['audit']; ?>" target="_blank"><i class="fa fa-bar-chart" title="Lighthouse Performance Report"></i></a> <?php } ?>
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