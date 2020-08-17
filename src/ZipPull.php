<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 17.08.2020
 * Time: 17:49
 */

namespace juraev\github_ci;


class ZipPull
{

    /**
     * @var string
     */
    private $url;

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


    public function __construct(
        $url,
        $path,
        $zipPath,
        $branch
    )
    {
        $this->url = $url;
        $this->path = $path;
        $this->zipPath = $zipPath;
        $this->branch = $branch;
    }

    /**
     * @return bool
     */
    public function pull()
    {
        $zipUrl = $this->url . '/archive/' . $this->branch . '.zip';
    }

    /**
     * @param string $url
     * @param string $zipPath
     * @return string | bool
     */
    private function getZip($url,$zipPath)
    {

        if( !is_dir( $zipPath ) )
        {
            mkdir($zipPath, 0777, true);
        }

        $file = file_get_contents($url);

        $fileName = $zipPath . '/' . $this->branch . '.zip';
        file_put_contents($fileName, $file);

        return $fileName;
    }

    /**
     * @param string $zip
     * @param string $path
     * @return bool
     */
    private function unzip($zip, $path)
    {

    }

}