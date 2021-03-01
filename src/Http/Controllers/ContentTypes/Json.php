<?php

namespace TCG\Voyager\Http\Controllers\ContentTypes;


class Json extends BaseType
{
    /**
     * @return array
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);
        
        $new_parameters = array();

        if(!empty($value))
        foreach ($value as $key => $val) {
            if($value[$key]){
                $new_parameters[] = $value[$key];
            }
        }
       
        return $new_parameters;
    }
}
