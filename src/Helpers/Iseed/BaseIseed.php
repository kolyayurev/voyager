<?php

/**
 * @source https://github.com/orangehill/iseed
 */

namespace TCG\Voyager\Helpers\Iseed;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class BaseIseed
{
    /**
     * Name of the database upon which the seed will be executed.
     *
     * @var string
     */
    protected $databaseName;

    /**
     * New line character for seed files.
     * Double quotes are mandatory!
     *
     * @var string
     */
    protected $newLineCharacter = PHP_EOL;

    /**
     * Desired indent for the code.
     * For tabulator use \t
     * Double quotes are mandatory!
     *
     * @var string
     */
    protected $indentCharacter = "    ";


    public function __construct(Filesystem $filesystem = null)
    {
        $this->files = $filesystem ?: new Filesystem;
    }

    public function readStubFile($file)
    {
        $buffer = file($file, FILE_IGNORE_NEW_LINES);
        return implode(PHP_EOL, $buffer);
    }


    /**
     * Get the path to the stub file.
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs';
    }

    /**
     * Adds new lines to the passed content variable reference.
     *
     * @param string    $content
     * @param int       $numberOfLines
     */
    private function addNewLines(&$content, $numberOfLines = 1)
    {
        while ($numberOfLines > 0) {
            $content .= $this->newLineCharacter;
            $numberOfLines--;
        }
    }

    /**
     * Adds indentation to the passed content reference.
     *
     * @param string    $content
     * @param int       $numberOfIndents
     */
    private function addIndent(&$content, $numberOfIndents = 1)
    {
        while ($numberOfIndents > 0) {
            $content .= $this->indentCharacter;
            $numberOfIndents--;
        }
    }

}
