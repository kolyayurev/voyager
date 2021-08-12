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
    protected function addNewLines(&$content, $numberOfLines = 1)
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
    protected function addIndent(&$content, $numberOfIndents = 1)
    {
        while ($numberOfIndents > 0) {
            $content .= $this->indentCharacter;
            $numberOfIndents--;
        }
    }

        /**
     * Prettify a var_export of an array
     * @param  array  $array
     * @return string
     */
    protected function prettifyArray($array, $indexed = true)
    {
        $content = ($indexed)
            ? var_export($array, true)
            : preg_replace("/[0-9]+ \=\>/i", '', var_export($array, true));

        $lines = explode("\n", $content);

        $inString = false;
        $tabCount = 3;
        for ($i = 1; $i < count($lines); $i++) {
            $lines[$i] = ltrim($lines[$i]);

            //Check for closing bracket
            if (strpos($lines[$i], ')') !== false) {
                $tabCount--;
            }

            //Insert tab count
            if ($inString === false) {
                for ($j = 0; $j < $tabCount; $j++) {
                    $lines[$i] = substr_replace($lines[$i], $this->indentCharacter, 0, 0);
                }
            }

            for ($j = 0; $j < strlen($lines[$i]); $j++) {
                //skip character right after an escape \
                if ($lines[$i][$j] == '\\') {
                    $j++;
                }
                //check string open/end
                else if ($lines[$i][$j] == '\'') {
                    $inString = !$inString;
                }
            }

            //check for openning bracket
            if (strpos($lines[$i], '(') !== false) {
                $tabCount++;
            }
        }

        $content = implode("\n", $lines);

        return $content;
    }
}
