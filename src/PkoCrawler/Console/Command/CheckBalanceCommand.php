<?php

namespace PkoCrawler\Console\Command;

use CL\Slack\Payload\ChatPostMessagePayload;
use CL\Slack\Transport\ApiClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CheckBalanceCommand extends AbstractPkoCommand
{
    protected function configure()
    {
        $this->setName('pko:check-balance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user     = $this->configuration['ipko']['user'];
        $pass     = $this->configuration['ipko']['pass'];
        $account  = $this->configuration['ipko']['account'];
        $dateFrom = date('Y-m-d', strtotime('-3 days'));
        $file     = 'pko' . time() . '.png';
        $path     = ROOT_PATH . '/web/screens/' . $file;
        $command
                  = "phantomjs --ignore-ssl-errors=yes --ssl-protocol=any web/pko.js {$user} {$pass} {$path} {$account} {$dateFrom}";
        $process  = new Process($command);
        $process->setTimeout(60);
        $output->write('Starting process' . PHP_EOL);
        $process->run(
            function ($type, $buffer) use ($output) {
                $output->write($buffer);
            }
        );
        $output->write('Process finished' . PHP_EOL);
        $this->notify($file, $path);
    }

    protected function notify($file, $screen)
    {
        $team  = $this->configuration['slack']['team'];
        $token = $this->configuration['slack']['token'];
        $user  = $this->configuration['slack']['user'];
        $room  = $this->configuration['slack']['room'];

        $client   = new \GuzzleHttp\Client();
        $response = $client->post(
            "https://{$team}.slack.com/api/files.upload",
            [
                'body' => [
                    'channels' => $room,
                    'token'    => $token,
                    'file'     => fopen($screen, 'r'),
                ]
            ]
        );

        $json = json_decode($response->getBody()->getContents());

        $client = new ApiClient($token);

        $payload = new ChatPostMessagePayload();
        $payload->setChannel($room);
        $payload->setText($json->file->url_download);
        $payload->setUsername($user);

        $client->send($payload);
    }
}