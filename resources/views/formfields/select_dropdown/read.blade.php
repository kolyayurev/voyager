@if(property_exists($row->details, 'options') && !empty($row->details->options->{$dataTypeContent->{$row->field}}))
    @include('voyager::multilingual.input-hidden-bread-read')
    <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
@endif
