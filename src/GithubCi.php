<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 11.08.2020
 * Time: 17:03
 */

namespace juraev\github_ci;


class GithubCi
{

    const GITHUB_URL = 'https://github.com/';

    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $zipPath;

    /**
     * @var string
     */
    private $branch;

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var string
     */
    private $log_dir;

    /**
     * @var string
     */
    private $log_filename = 'log.txt';

    public function __construct($config = [])
    {
        foreach ($config as $key => $value)
        {
            if( property_exists(self::class, $key ) )
            {
                $this->$key = $value;
            }
        }
    }

    public function run()
    {
        $handler = new WebHookHandler();
        $pushRepo = $handler->getPullRequestRepo();

        $result = false;
        if( $pushRepo === $this->repository)
        {
            $url = self::GITHUB_URL . $this->repository;
            $path = $this->getPath();
            $zipPath = $this->getZipPath();

            $puller = new ZipPull($url,$path,$zipPath,$this->branch);
            $result = $puller->pull();
        }


        if( $this->log )
        {
            $log = [];
            $log['request'] = $handler->getRequest();
            $log['result'] = $result;
            $this->log($log);
        }

        return $result;

    }

    private function getPath()
    {
        if($this->path)
        {
            $path = $this->path;
        }
        else
        {
            $path = dirname( dirname( dirname( dirname( __DIR__ ) ) ) );
        }
        return $path;
    }

    private function getZipPath()
    {
        if($this->zipPath)
        {
            $path = $this->zipPath;
        }
        else
        {
            $path = dirname( dirname( dirname( dirname( __DIR__ ) ) ) );
        }
        return $path;
    }

    private function log($data)
    {

        if($this->log_dir)
        {
            $log_dir = $this->log_dir;
        }
        else
        {
            $log_dir = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/git_log';
        }

        if( !is_dir($log_dir) )
        {
            if (!mkdir($concurrentDirectory = $log_dir, 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $data = json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;

        file_put_contents($log_dir . '/' . $this->log_filename,$data,FILE_APPEND);

    }

}