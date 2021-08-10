<?php
namespace TCG\Voyager\Helpers\ControlPanel;


class ControlButton 
{
    protected array $options;

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }
    /**
     * Setters
     */
    public function setOptions(array $options = [])
    {
        if(!empty($options))
            foreach ($options as $key => $value) {
                $this->{$key} = $value;
            }
    }
    /**
     * Getters
     */
    public function getUrl()
    {
        return $this->getOption('url');
    }
    public function getTitle()
    {
        return $this->getOption('title');
    }
    public function getColor()
    {
        return $this->getOption('color');
    }
    public function getIcon()
    {
        return $this->getOption('icon');
    }
    public function getOption($option,$default = null) 
    {
        return property_exists($this,$option) ? $this->{$option} : $default;
    }
    /**
     * Checks
     */
    public function hasIcon():bool
    {
        return $this->hasOption('icon');
    }
    public function hasColor():bool
    {
        return $this->hasOption('color');
    }
    public function hasOption($option) 
    {
        return property_exists($this,$option) && !empty($this->{$option})? true : false;
    }
}
