<div class="list-formfield" id="terms">


    <fieldset class=" row" v-for="(item, key) in items" :key="'preview_'+key">
        <div class=" col-xs-10" >
            <input type="text" class="form-control" v-model="item.text"  disabled/>
        </div>
        <div class="col-xs-2" >
            <button type="button" class="btn btn-xs btn-primary btn-edit" style="margin-top:0px;" @click="editItem(key)"><i class="voyager-edit"></i></button>
            <button type="button" class="btn btn-xs btn-danger btn-delete" style="margin-top:0px;" @click="deleteItem(key)"><i class="voyager-trash"></i></button>
        </div>
    </fieldset>

    <legend></legend>
    <el-form 
        :model="model" 
        :rules="rules" 
        ref="form" 
        label-position="top">
        <el-form-item prop="text">
            <el-input type="text"   v-model="model.text" placeholder="Текст"> </el-input>
        </el-form-item>

        <div class="col-xs-12" >
            <button type="button" class="btn btn-success btn-xs btn-add" style="margin-top:0px;" @click="addItem" v-if="!isEdit"><i class="voyager-plus"></i>Добавить</button>
            <button type="button" class="btn btn-success btn-xs btn-add" style="margin-top:0px;" @click="saveItem" v-if="isEdit"><i class="voyager-check"></i>Сохранить</button>
        </div>
    </el-form>

    <input type="hidden" name="{{$row->field}}" :value="printObject(items)"/>
</div>


@push('javascript')
<script>

    document.addEventListener('DOMContentLoaded', function(){

        new Vue({
            el:'#terms',
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
                }
            }
        });

    });
    
</script>
@endpush
