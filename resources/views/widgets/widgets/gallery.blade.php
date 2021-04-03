  
@php
    /**
     * $options = {
     * media_manager:{ ... },
     *  title: {
     *     title: String,
     *     placeholder: String, 
     *     default: String,  
     *  },
     *  description: {},
     *  url: {},
     * }
     *  
     */
@endphp
@php
    $manager_options = isset($options->media_manager)?$options->media_manager:new stdClass();

    $title =  isset($options->title)?$options->title:new stdClass();
    $description =  isset($options->description)?$options->description:new stdClass();
    $url =  isset($options->url)?$options->url:new stdClass();
@endphp

@php
    $dataTypeRows = $dataType->rows;
@endphp

@push('css')

<style>
    .el-card{
        min-width: 300px;
        max-width: 400px;
        margin-right: .5rem;
    }
    .el-form{
        margin-top: .5rem;
        padding: .5rem 1rem;
        border: 1px solid #dcdfe6;
        border-radius: 4px;
    }
    .el-carousel__item{
        display: flex;
        justify-content: center;
    }
    .el-form--label-top .el-form-item__label{
        padding: 0;
    }
    .widget-form .language-label{
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
    .dd-empty{
        min-height: 60px;
    }
    .widget-gallery{
        display: flex;
        overflow-x : auto;
    }
  </style>
@endpush

<form
    ref="form"
    role="form"
    class="form-edit-add widget-form"  {{--  important --}}
    id="widget_form_{{$dataTypeContent->getKey()}}"> {{--  important --}}
    @method("PUT")
    @csrf
    
    @php
        $row = $dataTypeRows->where('field', 'value')->first();
        $vue_instance_name = 'vue_form_'.$dataTypeContent->getKey().'_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n':'');
    @endphp
    @include('voyager::multilingual.input-hidden-bread-edit-add')
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>
    <draggable class="widget-gallery" v-if="items.length"v-model="items">
        <el-card :body-style="{ padding: '0px'}" v-for="(item, key) in items" :key="key">
            <div class="image-box">
                <img :src="storageLink(item.url)" class="image">
            </div>
            <div style="padding: 14px;">
                <span>@{{ item.title }}</span>
                <div class="bottom clearfix">
                <el-button type="primary" icon="el-icon-edit"  @click="editItem(key)" ></el-button>
                <el-button type="danger" icon="el-icon-delete" @click="deleteItem(key)" ></el-button>
                </div>
            </div>
        </el-card>
    </draggable>
    <el-alert
        v-else
        title="@lang('voyager::widgets.messages.empty_data')"
        type="info"
        description="@lang('voyager::widgets.messages.add_data')"
        show-icon
        :closable="false">
    </el-alert>
    <el-form 
        :model="model" 
        :rules="rules" 
        ref="vueForm" 
        label-position="top">
        <el-divider>@lang('voyager::widgets.messages.add_new_item')</el-divider>

        <el-form-item label="{{ $title->title??__('voyager::fields.title')}}" prop="title">
            <el-input type="text" v-model="model.title" placeholder="{{ $title->placeholder??__('voyager::fields.title') }}"> </el-input>
        </el-form-item>
        <el-form-item label="{{ $description->title??__('voyager::fields.description')}}" prop="description">
            <el-input type="textarea" :rows="2" v-model="model.description" placeholder="{{ $description->placeholder??__('voyager::fields.description') }}"> </el-input>
        </el-form-item>
       
        <el-form-item label="{{ $url->title??__('voyager::fields.image')}}" prop="url">
            <media-manager
                ref="mediaManager"
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

        <el-button type="success" icon="el-icon-circle-plus-outline" @click="addItem" v-if="!isEdit" >@lang('voyager::generic.add')</el-button>
        <el-button type="success" icon="el-icon-check" @click="saveItem" v-if="isEdit">@lang('voyager::generic.save')</el-button>
    </el-form>
    <div class="panel-footer">
        <el-button type="primary" @click.prevent="saveForm" :loading="prLoading">@lang('voyager::generic.save')</el-button>
    </div>
</form>



@push('vue')
    <script>
        var {{$vue_instance_name}} = new Vue({ // important
            el:'#widget_form_{{$dataTypeContent->getKey()}}', // important
            data(){
                return {
                    model:{
                        title: {!! printString($title->default??'') !!},
                        description: {!! printString($description->default??'') !!},
                        url: {!! printString($url->default??'') !!}
                    },
                    rules: {
                        title: [
                            { required: true, message: 'Обязательное поле', trigger: 'change' },
                        ],
                        url: [
                            { required: true, message: 'Обязательное поле', trigger: 'change' },
                        ],
                    },
                    items: {!! printArray(old('value',$dataTypeContent->value)) !!},
                    isEdit:false,
                    editIndex:false,
                    
                }
            },
            created(){
                this.updateLocaleData(this.items)
            },
            mounted(){
                vueFieldInstances['{{$vue_instance_name}}']=this
            },

            methods:{
                addItem(){
                    this.$refs.vueForm.validate((valid) => {
                        if (valid) {
                            this.items.push({...this.model})
                            this.$refs.mediaManager.close() 
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
                    this.$refs.mediaManager.open() 
                },
                saveItem(){
                    this.$refs.vueForm.validate((valid) => {
                    if (valid) {
                        this.items[this.editIndex] =  {...this.model}
                        this.editIndex = false
                        this.isEdit= false
                        this.$refs.mediaManager.close() 
                        this.clearForm()
                    } else {
                        return false;
                    }
                    });
                },
                saveForm(){
                    @if(is_bread_translatable($dataTypeContent))
                        window.multilingual.prepareData(); // important
                    @endif

                    var _this = this
                    let data = new FormData(this.$refs.form);
                    let url = '{{ route('voyager.'.$dataType->slug.'.moderate_update', $dataTypeContent->getKey()) }}';

                    _this.baseAxios(url, data, function (response) {
                        _this.successMsg(response.data.message);
                    },
                    function (response) {
                        _this.warningMsg(response.data.message);
                    });
                },
                deleteItem(key){
                    this.items.splice(key, 1);
                },
                clearForm(){
                    this.model.title = {!! printString($title->default??'') !!};
                    this.model.description = {!! printString($description->default??'') !!};
                    this.model.url = {!! printString($url->default??'') !!};
                    
                },
                printObject(obj){
                    return JSON.stringify(obj);
                },
                // important
                updateLocaleData(items){ 
                    this.items = this.isJsonValid(items)?JSON.parse(items):(items?items:[])
                },
                storageLink(url){
                    return '{{Storage::disk(config('voyager.storage.disk'))->url('/')}}'+url;
                }
            }
        });
    </script>
@endpush