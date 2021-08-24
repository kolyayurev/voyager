{{--   
   {
        "fields":[
            {
                "label":"fieldlabel", 
                "name":"fieldName",
                "component":"el-input", // ['el-input'], required
                "type":"textarea", // ['textarea','text']
                "placeholder":"placeholder text", 
                "default": "default text", // default value
                "rows":3, // only for textarea
                "rules":[{ "required": true, "message": "Обязательное поле", "trigger": "blur" }] // validation rules
                "col":12, // column
            },
        ],
        "displayField" : "fieldName"
    } 
--}}

@php
    $dataTypeRows = $dataType->rows;
    $widgetId = 'widget_form_'.$dataTypeContent->getKey();
    $fields = $options->fields;
    $displayField = $options->displayField;
@endphp

<form
    ref="form"
    role="form"
    class="form-edit-add widget-form array-builder-widget"  {{--  important --}}
    id="{{$widgetId}}"> {{--  important --}}
    @method("PUT")
    @csrf
    
    @php
        $row = $dataTypeRows->where('field', 'value')->first();
        $vue_instance_name = 'vue_form_'.$dataTypeContent->getKey().'_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n':'');
    @endphp
    @include('voyager::multilingual.input-hidden-bread-edit-add'){{--  important --}}
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>{{--  important --}}
    <draggable v-if="items.length" v-model="items">
        <div v-for="(item, key) in items" :key="key"  v-if="items.length" class="item"> 
            @{{ item[displayField] }}
            <div class="item__buttons">
                <el-button type="primary" icon="el-icon-edit"  @click="editItem(key)"   size="mini"></el-button>
                <el-button type="danger" icon="el-icon-delete" @click="deleteItem(key)"   size="mini"></el-button>
            </div>
        </div>
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
            <el-col v-for="field in fields" :md="field.col">
                <el-form-item  :label="field.label" :prop="field.name">
                    <component :is="field.component" :type="field.type" :rows="field.rows" v-model="model[field.name]" :placeholder="field.placeholder"> </component>
                </el-form-item>
            </el-col>
        </el-row>

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
            el:'#{{$widgetId}}', // important
            data(){
                return {
                    fields: {!! printArray($fields) !!},
                    displayField: {!! printString($displayField) !!},
                    model:{},
                    rules: {
                       
                    },
                    items: {!! printArray(old('value',$dataTypeContent->value)) !!},
                    isEdit:false,
                    editIndex:false,
                }
            },
            created(){
                this.init();
                this.updateLocaleData(this.items)  // important
            },
            mounted(){
                
                vueFieldInstances['{{$vue_instance_name}}']=this  // important
            },

            methods:{
                init(){
                    var _this = this;
                    this.fields.forEach(function(field){
                        console.log(field);
                        _this.$set(_this.model, field.name, field.default);
                        _this.$set(_this.rules, field.name, field.rules);
                    })
                },
                addItem(){
                    this.$refs.vueForm.validate((valid) => {
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
                saveForm(){ // important
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
                    for (key in this.model) {
                        this.model[key] = null
                    }
                },
                // important
                printObject(obj){
                    return JSON.stringify(obj);
                },
                // important
                updateLocaleData(items){ 
                    this.items = this.isJsonValid(items)?JSON.parse(items):(items?items:[])
                },
            }
        });
    </script>
@endpush