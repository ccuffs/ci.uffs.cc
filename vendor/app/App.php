<?php

class App {
    private $config;
    private $systems;
    private $data;
    private $stats;

    public function  __construct($base_dir) {
        $this->config = parse_ini_file(__DIR__ . '/../../app.ini');
        $this->systems = array();
        $this->data = array();

        $this->stats = array(
            'commits_total' => 0,
            'commits_today' => 0,
            'commits_week' => 0,
            'systems' => 0
        );

        $this->loadSystems($base_dir);
    }

    public function config($key, $default = null) {
        if(isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }

    public function stats($name, $fmt = '%04d') {
        if(!isset($this->stats[$name])) {
            throw new Exception('Unknown stats named "'.$name.'". Available are: ' . implode(', ', array_keys($this->stats)));
        }

        return sprintf($fmt, $this->stats[$name]);
    }

    public function getSystems() {
        return $this->systems;
    }

    public function getBranchStyle($name) {
        return isset($this->config['branch_style'][$name]) ? $this->config['branch_style'][$name] : 'info';
    }

    public function getSystem($dir_name) {
        if(!isset($this->systems[$dir_name])) {
            throw new Exception("Unknown system named '$dir_name'. Check if a folder named '$dir_name' exists.");
        }

        return $this->systems[$dir_name];
    }

    private function splitGitRepoURL($git_repo_url) {
        $parts = explode('/', $git_repo_url);

        if(count($parts) < 3) {
            $parts = ['', '', ''];
        }

        return array(
            'url' => $git_repo_url,
            'owner' => $parts[count($parts) - 2],
            'name' => $parts[count($parts) - 1],
        );
    }

    private function urlencode($string) {
        $replacements = array('%20');
        $entities = array('+');

        return str_replace($entities, $replacements, urlencode($string));
    }

    public function buildFSPath(...$segments) {
        return join(DIRECTORY_SEPARATOR, $segments);
    }

    private function createSystemEntry($dir_name) {
        $system_ini_dir = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'data', $dir_name.'.ini'));
        $ini_info = @parse_ini_file($system_ini_dir);

        if($ini_info === false) {
            $ini_info = parse_ini_file(__DIR__ . '/../../data/default.ini');
        }

        $base_info = array(
            'dir' => $dir_name,
            'branches' => array(),
            'commits' => array()
        );

        $system = array_merge($ini_info, $base_info);
        $system['repo'] = $this->splitGitRepoURL($system['git_repo_url']);

        $system['github_workflow_name'] = $this->urlencode($system['github_workflow_name']);

        return $system;
    }

    private function generateCommitURL($system_name, $branch_name, $commit_dir) {
        $commit_parts = pathinfo($commit_dir);
        $commit_url = $system_name . '/' . $branch_name . '/' . $commit_parts['basename'];

        return $commit_url;
    }

    private function generateAuditReportURL($system_name, $branch_name, $commit_dir) {
        $report_dir_name = $this->config['audit_report_dir_name'];
        $audit_path = $commit_dir . '/' . $report_dir_name;
        $audit_url = '';

        if(file_exists($audit_path)) {
            $audit_file = '';

            foreach(glob($audit_path . '/*.html') as $report) {
                $report_parts = pathinfo($report);
                $audit_file = $report_parts['basename'];
            }
            
            if($audit_file != '') {
                $commit_url = $this->generateCommitURL($system_name, $branch_name, $commit_dir);
                $audit_url = $commit_url . '/' . $report_dir_name . '/' . $audit_file;
            }
        }

        return $audit_url;
    }

    private function getCommitHash($commit_dir) {
        $commit_parts = pathinfo($commit_dir);
        return $commit_parts['basename'];
    }

    private function getBranchName($branch_dir) {
        $branch_parts = pathinfo($branch_dir);
        return $branch_parts['basename'];
    }

    private function sortCommitsByTime(array &$parsed_commits) {
        usort($parsed_commits, function($a, $b) {
            if ($a['time'] == $b['time']) {
                return 0;
            }
            return ($a['time'] > $b['time']) ? -1 : 1;
        });
    }

    private function countCommitStats(array $parsed_commits) {
        $yesterday = strtotime('-1 day');
        $last_week = strtotime('-1 week');

        foreach($parsed_commits as $commit) {
            $this->stats['commits_total']++;

            if($commit['time'] >= $yesterday) {
                $this->stats['commits_today']++;
            }

            if($commit['time'] >= $last_week) {
                $this->stats['commits_week']++;
            }
        }
    }

    private function loadSystems($base_dir) {
        foreach(glob($base_dir . '/*', GLOB_ONLYDIR) as $dir) {
            $item = pathinfo($dir);
            $name = $item['basename'];

            if(in_array($name, $this->config['exclude'])) {
                continue;
            }

            $this->stats['systems']++;
            $system = $this->createSystemEntry($name);

            foreach(glob($dir . '/*', GLOB_ONLYDIR) as $branch) {
                $branch_name = $this->getBranchName($branch);
                $system['branches'][] = $branch_name;

                foreach(glob($branch . '/*', GLOB_ONLYDIR) as $commit) {
                    $commit_url = $this->generateCommitURL($name, $branch_name, $commit);

                    $system['commits'][] = array(
                        'branch' => $branch_name,
                        'dir' => $commit,
                        'url' => $commit_url,
                        'sha' => $this->getCommitHash($commit),
                        'time' => filectime($commit),
                        'audit' => $this->generateAuditReportURL($name, $branch_name, $commit)
                    );
                }

                $this->sortCommitsByTime($system['commits']);               
                $this->countCommitStats($system['commits']);
            }

            $this->systems[$name] = $system;
        }
    }
}

?>