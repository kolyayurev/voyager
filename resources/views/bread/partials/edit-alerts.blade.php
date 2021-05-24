@if (count($errors) > 0)
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif