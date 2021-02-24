@extends('voyager::master')
{{-- @dd($activities) --}}
@section('content')
<div class="page-content edit-add container-fluid" id="page" v-cloak>
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Активность</h3>
                </div>
                <div class="panel-body" >
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Управление</h3>
                </div>
                <div class="panel-body">
                    <el-row class="" style="margin-bottom: 1rem; ">
                        <el-button type="warning" icon="el-icon-delete" @click="clearCache" :loading="prLoading">Очистить кэш
                        </el-button>
                    </el-row>

                </div>
            </div>
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">Другое</h3>
                </div>
                <div class="panel-body">
                    <el-row class="" style="margin-bottom: 1rem; ">
                        <a href="{{route('voyager.settings.create_group_permissions')}}" class="btn btn-primary ">
                            Создать права для групп настроек
                        </a>
                    </el-row>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('javascript')
<script>
    $('document').ready(function () {
        new Vue({
            el: '#page',
            data() {
                return {
                }
            },
            computed: {

            },
            methods: {
                clearCache() {
                    var _this = this;

                    let data = [];
                    // let url = this.route("voyager.ajax.clear_cache");
                    let url = {!! printString(route("voyager.ajax.clear_cache")) !!};

                    _this.baseAxios(url, data, function (response) {
                        _this.successMsg('Кэш очищен')
                    });
                },
             
            }
        })
    })

</script>
@endpush
