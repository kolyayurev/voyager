@php
    $id = 'list_'.$row->field;
    $vue_instance_name = 'vue_'.$row->field.(is_field_translatable($dataTypeContent, $row)?'_i18n ':'');
@endphp

<div class="list-formfield" id="{{ $id }}">
    @include('voyager::multilingual.input-hidden-bread-edit-add')
    <input type="hidden" name="{{$row->field}}" class="form-control is-vue" :value="printObject(items)" data-vue-instance="{{ $vue_instance_name }}"/>
    <div class="list-items">
        <div v-for="(item, key) in items" :key="'preview_'+key"  class="list-item"> 
            @{{ item.text }}
            <div class="list-item__buttons">
                <el-button type="primary" icon="el-icon-edit"  @click="editItem(key)"  size="mini"></el-button>
                <el-button type="danger" icon="el-icon-delete" @click="deleteItem(key)"  size="mini"></el-button>
            </div>
        </div>
    </div>
   
    <legend></legend>
    <el-form 
        :model="model" 
        :rules="rules" 
        ref="form" 
        label-position="top">
        <el-form-item prop="text">
            <el-input type="text"   v-model="model.text" placeholder="Текст"> </el-input>
        </el-form-item>

        <button type="button" class="btn btn-success btn-xs btn-add" style="margin-top:0px;" @click="addItem" v-if="!isEdit"><i class="voyager-plus"></i>Добавить</button>
        <button type="button" class="btn btn-success btn-xs btn-add" style="margin-top:0px;" @click="saveItem" v-if="isEdit"><i class="voyager-check"></i>Сохранить</button>
    </el-form>
   
</div>


@push('vue')
<script>

    var {{$vue_instance_name}} = new Vue({
        el:'#{{ $id }}',
        data(){
            return {
                model:{
                    text: '',
                },
                rules: {
                    text: [
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
            }
            @endif
        }
    });    

</script>
@endpush
