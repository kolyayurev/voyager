<?php 

namespace TCG\Voyager\Traits;

trait HasSeo
{
    public function getH1()
    {
        return $this->h1??$this->name??'';
    }
    public function getMetaTitle()
    {
        return $this->meta_title??$this->getH1()??setting('seo.meta_title')??'';
    }
    public function getMetaDescription()
    {
       return  $this->meta_description??$this->excerpt??setting('seo.meta_description')??'';
    }
}