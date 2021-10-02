<?php 

namespace TCG\Voyager\Traits;

use Voyager;

trait HasSeo
{
    public function getH1()
    {
        if(Voyager::translatable($this))
            return $this->getTranslatedAttribute('h1')??$this->getTranslatedAttribute('name')??'';
        else
            return $this->h1??$this->name??'';
    }
    public function getMetaTitle()
    {
        return (Voyager::translatable($this)?$this->getTranslatedAttribute('meta_title'):$this->meta_title)??$this->getH1()??setting('seo.meta_title')??'';
    }
    public function getMetaDescription()
    {
        if(Voyager::translatable($this))
            return $this->getTranslatedAttribute('meta_description')??$this->getTranslatedAttribute('excerpt')??setting('seo.meta_description')??'';
        else
            return  $this->meta_description??$this->excerpt??setting('seo.meta_description')??'';
    }
    public function getKeywords()
    {
        if(Voyager::translatable($this))
            return $this->getTranslatedAttribute('meta_keywords')??$this->getH1()??setting('seo.meta_keywords')??'';
        else
            return $this->meta_keywords??$this->getH1()??setting('seo.meta_keywords')??'';
    }
}