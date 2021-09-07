  
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
    $widgetId = 'widget_form_'.$dataTypeContent->getKey();
@endphp



<form
    ref="form"
    role="form"
    class="form-edit-add widget-form gallery-widget"  {{--  important --}}
    id="{{$widgetId}}"> {{--  important --}}
    @method("PUT")
    @csrf
    
    @php
        $row = $dataTypeRows->where('field', 'value')->first();
        $vue_instance_name = 'vue_form_'.$dataTypeContent->getKey().'_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n':'');
    @endphp
    @include('voyager::multilingual.input-hidden-bread-edit-add')
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>
    <draggable class="gallery-widget__items" v-if="items.length" v-model="items">
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

        <el-row :gutter="10">
            <el-col :md="8" >
                <el-form-item label="{{ $url->title??__('voyager::fields.image')}}" prop="url">
                    <div id="media_picker_{{ $row->field }}" class="media-picker-formfield">
                        <div class="media-picker-formfield__wrap">
                            <div class="media-picker-formfield__single" v-if="model.url">
                                <img class="media-picker-formfield__single-img" :src="'{{ Str::finish(Storage::disk(config('voyager.storage.disk'))->url('/'), '/') }}'+model.url">
                            </div>
                            <div class="media-picker-formfield__empty" v-else>
                                @lang('voyager::media.empty')
                            </div>
                            <div class="media-picker-formfield__btn-box">
                                <button class="media-picker-formfield__button btn" type="button" @click="openMediaPicker">@lang('voyager::generic.modify')</button>
                            </div>
                        </div>
                        <input type="hidden" v-model="model.url" />
                        <input type="hidden" name="url"/>
                        <v-dialog-media-picker v-model="model.url" ref="dialog"></v-dialog-media-picker>
                    </div>
                </el-form-item>
            </el-col>
            <el-col :md="16" >
                <el-form-item label="{{ $title->title??__('voyager::fields.title')}}" prop="title">
                    <el-input type="text" v-model="model.title" placeholder="{{ $title->placeholder??__('voyager::fields.title') }}"> </el-input>
                </el-form-item>
                <el-form-item label="{{ $description->title??__('voyager::fields.description')}}" prop="description">
                    <el-input type="textarea" :rows="2" v-model="model.description" placeholder="{{ $description->placeholder??__('voyager::fields.description') }}"> </el-input>
                </el-form-item>
            </el-col>
            
        </el-row>
        <el-button type="success" icon="el-icon-circle-plus-outline" @click="addItem" v-if="!isEdit" >@lang('voyager::generic.add')</el-button>
        <el-button type="success" icon="el-icon-check" @click="saveItem" v-if="isEdit" >@lang('voyager::generic.save')</el-button>
    </el-form>
    <div class="panel-footer">
        <el-button type="primary" @click.prevent="saveForm" :loading="prLoading">@lang('voyager::generic.save')</el-button>
    </div>
</form>



@push('vue')
    <script>
        var {{$vue_instance_name}} = new Vue({ // important
            el:'#{{$widgetId}}', // important
            data(){
                return {
                    maxItems: {{ printInt($options->max ?? 9999) }},
                    model:{
                        title: {!! printString($title->default??'') !!},
                        description: {!! printString($description->default??'') !!},
                        url: {!! printString($url->default??'') !!}
                    },
                    rules: {
                        title: [
                            { required: true, message: 'Обязательное поле', trigger: 'blur' },
                        ],
                        url: [
                            { required: true, message: 'Обязательное поле', trigger: 'change' },
                        ],
                    },
                    items: {!! printArray(old('value',$dataTypeContent->value)) !!},
                    isEdit:false,
                    editIndex:false,
                    manager_options:{
                        basePath:{!! printString( $manager_options->base_path ?? '/'.$dataType->slug.'/'. $dataTypeContent->getKey().'/') !!},
                        filename:{!! isset($manager_options->rename) ? printString($manager_options->rename) : 'null' !!},
                        allowMultiSelect: false,
                        maxSelectedFiles: 1,
                        minSelectedFiles: 0,
                        showFolders: {{ printBool($manager_options->show_folders ?? true) }},
                        showToolbar: {{ printBool($manager_options->show_toolbar ?? true) }},
                        allowUpload: {{ printBool($manager_options->allow_upload ?? true) }},
                        allowMove: {{ printBool($manager_options->allow_move ?? false) }},
                        allowDelete: {{ printBool($manager_options->allow_delete ?? true) }},
                        allowCreateFolder: {{ printBool($manager_options->allow_create_folder ?? true) }},
                        allowRename: {{ printBool($manager_options->allow_rename ?? true) }},
                        allowCrop: {{ printBool($manager_options->allow_crop ?? true) }},
                        allowedTypes: {!! printArray(isset($manager_options->allowed) && is_array($manager_options->allowed) ? $manager_options->allowed : ["image"]) !!},
                        preSelect: false,
                        expanded: {{ printBool($manager_options->expanded ?? true) }},
                        showExpandButton: true,
                        element: '#{{$widgetId}} input[name="url"]',
                        details: {!! printObject($manager_options ?? new class{}) !!},
                    },
                }
            },
            created(){
                this.updateLocaleData(this.items)
            },
            mounted(){
                vueFieldInstances['{{$vue_instance_name}}']=this
            },

            methods:{
                openMediaPicker(){
                    this.$refs.dialog.init(this.manager_options);
                },
                addItem(){
                    if(this.items.length < this.maxItems)
                        this.$refs.vueForm.validate((valid) => {
                            if (valid) {
                                this.items.push({...this.model})
                                // this.$refs.mediaManager.close() 
                                this.clearForm() 
                            } else {
                                return false;
                            }
                        });
                    else
                        this.warningMsg('{{ trans('voyager::widgets.messages.max_items',['count'=>$options->max]) }}');
                },
                editItem(key){
                    this.isEdit = true
                    this.editIndex = key
                    this.model = {...this.items[key]}
                },
                saveItem(){
                    this.$refs.vueForm.validate((valid) => {
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
                },
            }
        });
    </script>
@endpush