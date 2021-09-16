<?php 

namespace TCG\Voyager\Traits;

use Storage;
use Voyager;

use TCG\Voyager\Traits\Resizable;

trait HasMedia
{
    use Resizable;

    public function getDisk()
    {   
        return  config('filesystems.default');
    }
    public function getAvatarAttribute(){
        return $this->getAvatar();
    }
    public function getImagePathAttribute()
    {   
        return $this->image? $this->getImagePath('image'): null ;
    }

    public function getIconPathAttribute()
    {   
        return $this->icon? $this->getImagePath('icon'): null ;
    }

    public function getImagePath(string $string,$size=null)
    {
        if(!empty($size))
            $path = Voyager::image($this->getThumbnail($this->{$string},$size));

        if(empty($path))
            $path = Voyager::image($this->{$string});

        return $path;
    }    

    public function getPhotosDirectory(){
        return $this->photos_directory??\Str::of(self::getTable())->camel()->kebab(); 
    }

    public function getPhotosFields()
    {
        return $this->photos_fields;
    }  

    public function getTypePhotoField($name)
    {
        $field = array_filter($this->getPhotosFields(), function($item) use ($name) {
            return array_key_exists('name',$item) && $item['name'] == $name;
        });
        if(empty( $field))
            return null;
        $field = array_values($field);
        return array_key_exists('type',$field[0]) ? $field[0]['type'] : null;
    }

    public function clearPhotosFiles()
    {

        try{

            foreach ($this->getPhotosFields() as $field) {
                
                if(array_key_exists('type',$field) && $field['type'] == 'array')
                {
                    $photos = json_decode($this->{$field['name']});
                    
                    if(!empty($photos))
                    foreach ($photos as $item) {

                        Storage::disk($this->getDisk())->delete($item);
                            
                        if(array_key_exists('thumbnails',$field))
                        foreach ($field['thumbnails'] as $thumbnail) {
                            Storage::disk($this->getDisk())->delete($this->getThumbnail($item,$thumbnail));
                        }
                    }
                }
                elseif(array_key_exists('type',$field) && $field['type'] == 'json')
                {
                    $photos = json_decode($this->{$field['name']},true);

                    if(!empty($photos))
                    foreach ($photos as $photo) {
                        if(array_key_exists('field',$field))
                        {
                            Storage::disk($this->getDisk())->delete($photo[$field['field']]);
                        }
                        else
                            continue;
                            

                        if(array_key_exists('thumbnails',$field))
                        foreach ($field['thumbnails'] as $thumbnail) {
                            Storage::disk($this->getDisk())->delete($this->getThumbnail($photo[$field['field']],$thumbnail));
                        }
                    }
                }
                elseif(array_key_exists('type',$field) && $field['type'] == 'single')
                {
                    $image = $this->{$field['name']};
                    Storage::disk($this->getDisk())->delete($image);

                    if(array_key_exists('thumbnails',$field))
                    foreach ($field['thumbnails'] as $thumbnail) {
                        Storage::disk($this->getDisk())->delete($this->getThumbnail($image,$thumbnail));
                    }
                }
               
                
            }

            Storage::disk($this->getDisk())->deleteDirectory($this->getPhotosDirectory().'/'.$this->id);

            return true;

        }
        catch (Exception $e) {
            
            Log::error('clearPhotosFiles: Can not delete files');
            Log::error($e->getMessage());
            return false;
        }
    }
    
    public function hasPhotos(){
        return  !empty($this->getPhotos())?true : false;
    }

    public function getPhotosField():string
    {
        return 'photos';
    }
    public function getPhotos($column = null):array
    {   
        $column = $column??$this->getPhotosField();

        $array = json_decode($this->{$column},true);

        $photos = [];

        $type = $this->getTypePhotoField($column);  

        if(!empty($array))
        foreach ($array as $item) {
            switch ($type) {
                case 'array':
                    array_push($photos,$item);
                    break;
            }
            
        }
        
        return $photos;
    }

    public function getPhotosPath($column = null,$size = null):array
    {
        $column = $column??$this->getPhotosField();

        $array = $this->getPhotos($column);

        $photos = [];

        $type = $this->getTypePhotoField($column);        

        foreach ($array as $item) {
            if(!empty($size))
                $path = Voyager::image($this->getThumbnail($item,$size));
            else
                $path = Voyager::image($item);

            array_push($photos,$path);
        }

        return $photos;
    }
    public function setPhotos(array $photos){
        return $this->photos = json_encode($photos);
    }
    
    public function getAvatar(){
        $photos = $this->getPhotos();
        return Voyager::image(empty($photos) ? setting('site.default_club_photo'): $photos[0]);
    }

    public function deletePhoto($photo){
        try
        {
            $photos = json_decode($this->photos,true);
             
            if(!$photo || !$photos)
                throw new Exception('Нет фото');

            $key = array_search($photo,$photos);

            unset($photos[$key]);

            $this->photos = array_values($photos);

            $this->update();

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }


    
    public function addPhoto($photo){
        try
        {
            $photos =  is_array($this->photos)?$this->photos:json_decode($this->photos,true);

            if(!$photos)
                $photos= Array(); 
            array_push($photos,$photo);

            $this->photos = array_values($photos);

            $this->update();

            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }


    /**
     * getAllPhotos
     *
     * @return array
     */
    public function getAllPhotos($column = null,$size = null):array
    {
        $column = $column??$this->getPhotosField();

        $photos = $this->getPhotos($column,$size);

        return array_unique($photos);
    }
    
    /**
     * getAllPhotos
     *
     * @return array
     */
    public function getAllPhotosPath($column = null,$size = null):array
    {
        $column = $column??$this->getPhotosField();

        $photos = $this->getPhotosPath($column,$size);

        return array_unique($photos);
    }
}