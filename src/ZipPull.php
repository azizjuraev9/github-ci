<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 17.08.2020
 * Time: 17:49
 */

namespace juraev\github_ci;


use ZipArchive;

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

        $zipFile = $this->getZip($zipUrl,$this->zipPath);

        if( $zipFile === false )
            return false;

        return $this->unzip($zipFile,$this->path);
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
            if (!mkdir($zipPath, 0777, true) && !is_dir($zipPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $zipPath));
            }
        }

        $file = file_get_contents($url);

        if($file === false)
        {
            return false;
        }

        $fileName = $zipPath . '/' . $this->branch . '.zip';
        $res = file_put_contents($fileName, $file);

        if($res === false)
        {
            return false;
        }

        return $fileName;
    }

    /**
     * @param string $zip
     * @param string $path
     * @return bool
     */
    private function unzip($zip, $path)
    {
        $archive = new ZipArchive;
        $res = $archive->open($zip);
        if ($res === true) {
            $archive->extractTo($path);
            $archive->close();
            return true;
        }
        return false;
    }

}