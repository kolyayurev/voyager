<?php

namespace TCG\Voyager\FormFields;

interface HandlerInterface
{
    public function handle($row, $dataType, $dataTypeContent,$type);

    public function createContent($row, $dataType, $dataTypeContent, $options ,$type);

    public function supports($driver);

    public function getCodename();

    public function getName();
}
