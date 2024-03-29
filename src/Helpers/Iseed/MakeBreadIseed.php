<?php

/**
 * @source https://github.com/orangehill/iseed
 */

namespace TCG\Voyager\Helpers\Iseed;

use Voyager;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class MakeBreadIseed extends BaseIseed
{

    public function __construct(Filesystem $filesystem = null)
    {
        parent::__construct($filesystem);
    }

    /**
     * Generates a seed file.
     * @param  string   $slug
     * @return bool
     */
    public function generateSeed($slug)
    {
       
        // Generate class name
        $className = $this->generateClassName($slug);

        // Get template for a seed file contents
        $stub = $this->readStubFile($this->getStubPath() . '/bread.stub');

        // Get a app/database/seeds path
        $seedsPath = $this->getPath($className);

        // Get a populated stub file
        $seedContent = $this->populateStub(
            $className,
            $stub,
            $slug,
        );

        // Save a populated stub
        $this->files->put($seedsPath, $seedContent);

        chmod($seedsPath,0766);

        return $this->getRelativePath($className);
    }

    
    /**
     * Return folder 
     * @return string
     */
    public function getFolder()
    {
        return config('voyager.seeds.make_bread.path','/database/seeders/');
    }

    /**
     * Return relative path 
     * @return string
     */
    public function getRelativePath($name)
    {
        return $this->getFolder() . $name . '.php';
    }
    /**
     * Create the full path name to the seed file.
     * @param  string  $name
     * @return string
     */
    public function getPath($name)
    {
        return base_path() .$this->getRelativePath($name);
    }

    /**
     * Get the Data
     * @param  string $slug
     * @return Array
     */
    public function getData($slug)
    {
        $dataType = Voyager::model('DataType')->where('slug',$slug)->first();
        $dataRows = $dataType->rows;

        return [$dataType,$dataRows];
    }

    /**
     * Generates a seed class name (also used as a filename)
     * @param  string  $slug
     * @return string
     */
    public function generateClassName($slug)
    {
        return 'VoyagerBread' . \Str::studly($slug) .  'Seeder';
    }

    /**
     * Populate the place-holders in the seed stub.
     * @param  string   $class
     * @param  string   $stub
     * @param  string   $slug
     * @return string
     */
    public function populateStub($class, $stub, $slug)
    {
        // Get the data
        [$dataType,$dataRows] = $this->getData($slug);

        $stub = str_replace('{{class}}', $class, $stub);

        $stub = str_replace('{{slug}}', $slug, $stub);

        $stub = str_replace('{{data_type}}', $this->prettifyArray($dataType->makeHidden(['id','slug','table_name','created_at','updated_at'])->attributesToArray()), $stub);

        $dataTypeTranslationsInserts = '';

        $dataTypeTranslations = $dataType->translations;

        if($dataTypeTranslations->count())
        {
            foreach ($dataTypeTranslations as $translation) {
                $this->addNewLines($dataTypeTranslationsInserts);
                $this->addIndent($dataTypeTranslationsInserts, 2);
                $dataTypeTranslationsInserts .= sprintf('$translation = $this->trans(%s,$dataType->getKey());',$this->prettifyArray(\Arr::only($translation->attributesToArray(),['table_name','column_name','locale'])));
                $this->addNewLines($dataTypeTranslationsInserts);
                $this->addIndent($dataTypeTranslationsInserts, 2);
                $dataTypeTranslationsInserts .= sprintf('if (!$translation->exists) $translation->fill(%s)->save();',$this->prettifyArray(\Arr::only($translation->attributesToArray(),['value'])));
            }
        }

        $stub = str_replace('{{dataTypeTranslations}}', $dataTypeTranslationsInserts , $stub);

        $dataRowsInserts = '';

        if($dataRows->count())
        {
            foreach ($dataRows as $dataRow) {
                $this->addNewLines($dataRowsInserts);
                $this->addIndent($dataRowsInserts, 2);
                $dataRowsInserts .= sprintf('$dataRow = $this->dataRow($dataType, \'%s\');',$dataRow->field);
                $this->addNewLines($dataRowsInserts);
                $this->addIndent($dataRowsInserts, 2);
                $dataRowsInserts .= sprintf('if (!$dataRow->exists) $dataRow->fill(%s)->save();',$this->prettifyArray($dataRow->makeHidden(['id','data_type_id','field'])->attributesToArray()));

                $dataRowTranslations = $dataRow->translations;

                if($dataRowTranslations->count())
                {
                    foreach ($dataRowTranslations as $translation) {
                        $this->addNewLines($dataRowsInserts);
                        $this->addIndent($dataRowsInserts, 2);
                        $dataRowsInserts .= sprintf('$translation = $this->trans(%s,$dataRow->getKey());',$this->prettifyArray(\Arr::only($translation->attributesToArray(),['table_name','column_name','locale'])));
                        $this->addNewLines($dataRowsInserts);
                        $this->addIndent($dataRowsInserts, 2);
                        $dataRowsInserts .= sprintf('if (!$translation->exists) $translation->fill(%s)->save();',$this->prettifyArray(\Arr::only($translation->attributesToArray(),['value'])));
                    }
                }
            }
        }

        $stub = str_replace('{{data_rows}}', $dataRowsInserts , $stub);


        return $stub;
    }











}
