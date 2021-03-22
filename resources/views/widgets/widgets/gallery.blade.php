@extends('voyager::widgets.layouts.master')

@php
    $dataTypeRows = $dataType->rows;
    $row = $dataTypeRows->where('field', 'value')->first();
    $vue_instance_name = 'vue_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n ':'');
@endphp

@push('css')

<style>
    .el-card{
        max-width: 400px;
    }
    .el-carousel__item{
        display: flex;
        justify-content: center;
    }
    .language-label{
        display: none;
    }
    .bottom {
      margin-top: 13px;
      line-height: 12px;
    }
  
    .button {
      padding: 0;
      float: right;
    }
  
    .image-box {
        height: 200px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-box > img {
      max-width: 100%;
      display: block;
    }
  
    .clearfix:before,
    .clearfix:after {
        display: table;
        content: "";
    }
    
    .clearfix:after {
        clear: both
    }
  </style>
@endpush
[{title:"dsf",url:"sdfdf/asdsa/sad.jpg"},{title:"dsf",url:"sdfdf/asdsa/sad.jpg"},{title:"dsf",url:"sdfdf/asdsa/sad.jpg"}]
@section('form')
    @include('voyager::multilingual.input-hidden-bread-edit-add')
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>
    <el-carousel :autoplay="false" type="card" v-if="items.length" trigger="click">
        <el-carousel-item v-for="(item, key) in items" :key="key" >
            <el-card :body-style="{ padding: '0px'}" >
                <div class="image-box">
                    <img :src="storageLink(item.url)" class="image">
                </div>
                <div style="padding: 14px;">
                  <span>@{{ item.title }}</span>
                  <div class="bottom clearfix">
                    <el-button type="primary" icon="el-icon-edit"  @click="editItem(key)" plain></el-button>
                    <el-button type="danger" icon="el-icon-delete" @click="deleteItem(key)" plain></el-button>
                  </div>
                </div>
            </el-card>
        </el-carousel-item>
    </el-carousel>
    <el-divider></el-divider>
    <el-form 
        :model="model" 
        :rules="rules" 
        ref="form" 
        label-position="top">
        <el-form-item prop="title">
            <el-input type="text" v-model="model.title" placeholder="Заголовок"> </el-input>
        </el-form-item>
        @php
            $manager_options =  isset($options->media_manager)?$options->media_manager:[];
        @endphp
        <el-form-item prop="url">
            <media-manager
                base-path="{{ $manager_options->base_path ?? '/'.$dataType->slug.'/'. $dataTypeContent->getKey()}}"
                filename="{{ $manager_options->rename ?? 'null' }}"
                :allow-multi-select="false"
                :max-selected-files="1"
                :min-selected-files="0"
                :show-folders="{{ var_export($manager_options->show_folders ?? true, true) }}"
                :show-toolbar="{{ var_export($manager_options->show_toolbar ?? true, true) }}"
                :allow-upload="{{ var_export($manager_options->allow_upload ?? true, true) }}"
                :allow-move="{{ var_export($manager_options->allow_move ?? false, true) }}"
                :allow-delete="{{ var_export($manager_options->allow_delete ?? true, true) }}"
                :allow-create-folder="{{ var_export($manager_options->allow_create_folder ?? true, true) }}"
                :allow-rename="{{ var_export($manager_options->allow_rename ?? true, true) }}"
                :allow-crop="{{ var_export($manager_options->allow_crop ?? true, true) }}"
                :allowed-types="{{ isset($manager_options->allowed) && is_array($manager_options->allowed) ? json_encode($manager_options->allowed) : '["image"]' }}"
                :pre-select="false"
                :expanded="{{ var_export($manager_options->expanded ?? false, true) }}"
                :show-expand-button="true"
                :element="'input[name=&quot;url&quot;]'"
                :details="{{ json_encode($manager_options ?? []) }}"
                v-model="model.url"
            ></media-manager>
            <input type="hidden" v-model="model.url" />
            <input type="hidden" name="url"/>
        </el-form-item>

        <el-button type="success" icon="el-icon-circle-plus-outline" @click="addItem" v-if="!isEdit" plain>@lang('voyager::generic.add')</el-button>
        <el-button type="success" icon="el-icon-check" @click="saveItem" v-if="isEdit">@lang('voyager::generic.save')</el-button>
    </el-form>
@endsection



@push('vue')
    <script>
        var {{$vue_instance_name}} = new Vue({
            el:'#form',
            data(){
                return {
                    model:{
                        title: '',
                        url: ''
                    },
                    rules: {
                        title: [
                            { required: true, message: 'Обязательное поле', trigger: 'blur' },
                        ],
                        url: [
                            { required: true, message: 'Обязательное поле', trigger: 'blur' },
                        ],
                    },
                    items: {!! printArray($dataTypeContent->{$row->field}) !!},
                    isEdit:false,
                    editIndex:false,
                    
                }
            },
            mounted(){
                vueFieldInstances['{{$vue_instance_name}}']=this
            },
            methods:{
                addItem(){
                    this.$refs.form.validate((valid) => {
                        if (valid) {
                            this.items.push({...this.model}) 
                            this.clearForm() 
                        } else {
                            return false;
                        }
                    });
                    
                },
                editItem(key){
                    this.isEdit = true
                    this.editIndex = key
                    this.model = {...this.items[key]}
                },
                saveItem(){
                    this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.items[this.editIndex] =  {...this.model}
                        this.editIndex = false
                        this.isEdit= false
                        this.clearForm()
                    } else {
                        return false;
                    }
                    });
                    
                },
                deleteItem(key){
                    this.items.splice(key, 1);
                },
                clearForm(){
                    for (var key in this.model) {
                        this.model[key] = '';
                    }
                },
                printObject(obj){
                    return JSON.stringify(obj);
                },
                @if (is_field_translatable($dataTypeContent, $row) )
                updateLocaleData(items){
                    this.items = this.isJsonValid(items)?JSON.parse(items):(items?items:[])
                },
                @endif
                storageLink(url){
                    console.log('{{Storage::disk(config('voyager.storage.disk'))->url('/')}}'+url);
                    return '{{Storage::disk(config('voyager.storage.disk'))->url('/')}}'+url;
                }
            }
        });
    </script>
@endpush