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
    <link rel="stylesheet" type="text/css" href="./css/app.css?20200811">
</head>

<body id="top">
    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-md no-gutters">
                <div class="col-2 text-left">
                    <h1><i class="fa fa-area-chart"></i>ci.uffs.cc</h1>
                </div>

                <div class="collapse navbar-collapse justify-content-center col-8" id="navbarNav4">
                    <ul class="navbar-nav justify-content-center">
                        <li class="nav-item"></li>
                    </ul>
                </div>

                <ul class="navbar-nav col-2 justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/ccuffs/"><i class="fa fa-github fa-2x"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel">
                    <div class="panel-body">
                        <h3><i class="fa fa-bar-chart"></i> Stats</h3>
                        <hr>
                        <p>
                            This dashboard shows the statistics of CI buils related to the infra-strucutre of the <a href="https://cc.uffs.edu.br" target="_blank">Computer Science program</a> (and partners) at <a href="http://www.uffs.edu.br" target="_blank">Federal University of Fronteira Sul</a>. Numbers presented below are gathered from CI builds. They might be different from the real amount of commits shown in the projects' repository. 
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

        <div class="row">
            <div class="col-12">
                <h3><i class="fa fa-binoculars" aria-hidden="true"></i> Summary</h3>
                <table class="table table-hover table-responsive-sm no-footer" role="grid">
                    <thead>
                        <tr>
                            <th style="width: 10%;"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($app->getSystems() as $id => $system) { ?>
                            <tr>
                                <td>
                                    <div class="float-left">
                                        <?php if(!empty($system['build_status_badge_url'])) { ?><img src="<?php echo $system['build_status_badge_url']; ?>" title="Build status" /><?php } ?>
                                    </div>
                                </td>    
                                <td>
                                    <a href="#<?php echo $id; ?>"><?php echo $id ?></a> <small class="muted"><?php echo $system['name']; ?></small>
                                    <?php if(!empty($system['repo']['url'])) { ?><a href="<?php echo $system['repo']['url']; ?>" target="_blank" title="Github repository"><i class="fa fa-github muted"></i></a><?php } ?>
                                    <?php if(!empty($system['production_url'])) { ?><a href="<?php echo $system['production_url']; ?>" target="_blank" title="Production URL"><i class="fa fa-gg-circle muted"></i></a><?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
            foreach($app->getSystems() as $id => $system) {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="panel panel-filled">
                    <div class="panel-heading status-list">
                        <h2 class="float-left" id="<?php echo $id; ?>"><i class="fa fa-server"></i> <?php echo $id; ?><small class="muted"><?php echo $system['name']; ?></small></h2>
                        <?php if(!empty($system['repo']['url'])) { ?><a href="<?php echo $system['repo']['url']; ?>" target="_blank" title="Github repository"><i class="fa fa-github muted"></i></a><?php } ?>
                        <?php if(!empty($system['production_url'])) { ?><a href="<?php echo $system['production_url']; ?>" target="_blank" title="Production URL"><i class="fa fa-gg-circle muted"></i></a><?php } ?>
                        <div class="float-right">
                            <?php if(!empty($system['build_status_badge_url'])) { ?><img src="<?php echo $system['build_status_badge_url']; ?>" title="Build status" /><?php } ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="tableServices-<?php echo $id; ?>" class="table table-striped table-hover table-responsive-sm no-footer" role="grid">
                            <thead>
                                <?php
                                    $qrCodeFmt = isset($system['qr_code_fmt']) ? $system['qr_code_fmt'] : '';
                                    $commitLink = count($system['commits']) >= 1 ? $system['commits'][array_key_first($system['commits'])]['url'] : '';
                                    $qrLink = sprintf($qrCodeFmt, $app->config('base_url'), $commitLink);
                                ?>
                                <?php if(!empty($qrCodeFmt) && !empty($commitLink)) { ?>
                                    <tr role="row" class="most-recent">
                                        <td>
                                            <img src="http://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($qrLink); ?>&size=512x512&color=71C9B8&bgcolor=343434" alt="QR code" class="qr-code" />
                                        </td>
                                        <td colspan="3">
                                            <h3>Mobile access</h3>
                                            <p>This project was flagged as an application available on mobile platforms. <br />Scan the QR code using your mobile device to acess the most recent build.</p>
                                            <p><a class="muted" href="<?php echo $qrLink; ?>"><?php echo $qrLink; ?></a></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr role="row">
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 20%">Date</th>
                                    <th style="width: 50%">Commit</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $row = 0; ?>
                                <?php foreach($system['commits'] as $commit) { ?>
                                    <tr role="row" <?php echo ($row > $app->config('view_max_rows', 20) ? 'style="display:none;"' : ''); ?> class="row-<?php echo $id; ?>">
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
                                    <?php $row++; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if($row > $app->config('view_max_rows')) { ?>
                            <div style="text-align: center;">Show more</div>
                        <?php } ?>
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
                <div class="col-5 text-md-left">
                    <h3>About</h3>
                    <p>This website was made by the infra-structure team of the <a href="https://cc.uffs.edu.br">Computer Science program</a> at the <a href="http://www.uffs.edu.br">Federal University of Fronteira Sul</a>, campus Chapecó/SC, Brazil.</p>
                    
                    <p>Contact information:</p>
                    <p><a href="mailto:computacao.ch@uffs.edu.br"><i class="fa fa-envelope-o"></i> computacao.ch@uffs.edu.br</a></p>
                </div>

                <div class="col-1"></div>

                <div class="col-3">
                    <h3>Links</h3>
                    <li><a href="https://cc.uffs.edu.br">Ciência da Computação</a></li>
                    <li><a href="https://grintex.uffs.cc">Grintex</a></li>
                    <li><a href="http://www.uffs.edu.br">UFFS</a></li>
                </div>

                <div class="col-1"></div>

                <div class="col-2">
                    <h3>Social</h3>
                    <li><a href="https://github.com/ccuffs"><i class="fa fa-github"></i> Github</a></li>
                    <li><a href="https://twitter.com/computacaouffs"><i class="fa fa-twitter"></i> Twitter</a></li>
                    <li><a href="https://instagram.com/computacaouffs"><i class="fa fa-instagram"></i> Instagram</a></li>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <p class="muted">© <?php echo date('Y'); ?> All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>