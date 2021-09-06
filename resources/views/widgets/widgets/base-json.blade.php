  

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
    <draggable v-if="items.length" v-model="items" class="list-items">
        <div v-for="(item, key) in items" :key="key"  v-if="items.length" class="list-item"> 
            @{{ item.key + ' - ' + item.value  }}
            <div class="list-item__buttons">
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
            <el-col :md="12" >
                <el-form-item label="{{ __('voyager::fields.key') }}" prop="key">
                    <el-input type="text" v-model="model.key" > </el-input>
                </el-form-item>
            </el-col>
            <el-col :md="12" >
                <el-form-item label="{{ __('voyager::fields.value') }}" prop="value">
                    <el-input type="text" :rows="2" v-model="model.value"> </el-input>
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
                model:{
                    key: null,
                    value: null,
                },
                rules: {
                    key: [
                        { required: true, message: 'Обязательное поле', trigger: 'blur' },
                    ],
                    value: [
                        { required: true, message: 'Обязательное поле', trigger: 'blur' },
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
                for (var key in this.model) {
                    this.model[key] = '';
                }
            },
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