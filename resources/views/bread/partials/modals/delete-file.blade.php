<div class="modal fade modal-danger" id="confirm_delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
            </div>

            <div class="modal-body">
                <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete File Modal -->

@push('javascript')
<script>
    var params = {};
    var $file;
    function deleteHandler(tag, isMulti) {
        return function() {
        $file = $(this).siblings(tag);

        params = {
            slug:   '{{ $dataType->slug }}',
            filename:  $file.data('file-name'),
            id:     $file.data('id'),
            field:  $file.parent().data('field-name'),
            multi: isMulti,
            _token: '{{ csrf_token() }}'
        }

        $('.confirm_delete_name').text(params.filename);
        $('#confirm_delete_modal').modal('show');
        };
    }
    $('document').ready(function () {

        $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
        $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
        $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
        $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

        
        $('#confirm_delete').on('click', function(){
            $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                if ( response
                    && response.data
                    && response.data.status
                    && response.data.status == 200 ) {

                    toastr.success(response.data.message);
                    $file.parent().fadeOut(300, function() { $(this).remove(); })
                } else {
                    toastr.error("Error removing file.");
                }
            });

            $('#confirm_delete_modal').modal('hide');
        });
    });
</script>

@endpush