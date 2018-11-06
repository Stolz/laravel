<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class Command extends BaseCommand
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create progress bar.
     *
     * @param  int $total
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function createProgressBar(int $total): ProgressBar
    {
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->setFormat(" %message%\n %current%/%max% [%bar%] %percent:3s%% %estimated:-6s%");
        $progressBar->setMessage('Start');
        $progressBar->start();

        return $progressBar;
    }
}
