{{--   
   {
        "fields":[
            {
                "label":"fieldlabel", 
                "name":"fieldName",
                "component":"el-input", // ['el-input','el-input-number','el-time-select','el-rate',...], required
                "default": "default text", // default value
                "rules":[{ "required": true, "message": "Обязательное поле", "trigger": "blur" }], // validation rules
                "props": { }, // component props, example { "rows":3 , "type":"textarea", "placeholder":"text" } for textarea
                "col":12 // column,
            }
        ],
        "displayValue" : {"body":"return 'text:' + item.field1;"} // this is body of function. function has one parameter "item"
    } 
--}}

@php
    $dataTypeRows = $dataType->rows; // important
    $widgetId = 'widget_form_'.$dataTypeContent->getKey(); // important
    $row = $dataTypeRows->where('field', 'value')->first(); // important
    $vue_instance_name = 'vue_form_'.$dataTypeContent->getKey().'_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n':''); // important
@endphp

<form
    ref="form"
    role="form"
    class="form-edit-add widget-form array-builder-widget"  {{--  important --}}
    id="{{$widgetId}}"> {{--  important --}}
    @method("PUT"){{--  important --}}
    @csrf{{--  important --}}
    
    @include('voyager::multilingual.input-hidden-bread-edit-add'){{--  important --}}
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>{{--  important --}}
    <draggable v-if="items.length" v-model="items">
        <div v-for="(item, key) in items" :key="key"  v-if="items.length" class="item"> 
            @{{ functions.displayValue(item)  }}
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
            <el-col v-for="field in options.fields" :md="field.col" :key="field.name">
                <el-form-item  :label="field.label" :prop="field.name">
                    <component :is="field.component" v-bind="field.props" v-model="model[field.name]"> </component>
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
                    options: {!! printObject($options) !!},
                    model:{},
                    rules: {},
                    items: {!! printArray(old('value',$dataTypeContent->value)) !!},
                    isEdit:false,
                    editIndex:false,
                    functions:{}
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
                    _this.options.fields.forEach(function(field){
                        _this.$set(_this.model, field.name, field.default);
                        _this.$set(_this.rules, field.name, field.rules);
                    })
                    _this.$set(_this.functions,'displayValue' , new Function("item",_this.options.displayValue.body));
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
                displayValue(item) { }
            }
        });
    </script>
@endpush