<form  method="get" class="form-search" id="search"  @prevet.submit="concole.log('sub')">
    <el-row :gutter="10">
        <el-col :sm="12" :md="4">
            <el-select v-model="model.field">
                <el-option v-for="(item,index) in fields" :label="item.name" :value="index" :key="index" >
                </el-option>
            </el-select>
        </el-col>
        <el-col :sm="12" :md="4">
            <el-select v-model="model.filter">
                <el-option value="contains" value="contains" label="contains"></el-option>
                <el-option value="equals" value="equals" label="="></el-option>
            </el-select>
        </el-col>
        <el-col :xs="18" :sm="18" :md="14">
            {{-- v-bind="field.props" --}}
            <component :is="getFieldType()"   placeholder="{{ __('voyager::generic.search') }}" v-model="model.text" clearable></component>
        </el-col>
        <el-col :xs="6"  :sm="6" :md="2">
            <el-button class="form-search__btn-submit"  icon="el-icon-search" @click="$el.submit()"></el-button>
        </el-col>
    </el-row>
    <input type="hidden" v-model="model.field"  name="key">
    <input type="hidden" v-model="model.filter"  name="filter">
    <input type="hidden" v-model="model.text"  name="s">
    
    @if (Request::has('sort_order') && Request::has('order_by'))
        <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
        <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
    @endif
</form>

@once
    @push('vue')
    <script>
        new Vue({
            el:'#search',
            data(){
                return{
                    fields: {!! printArray($searchableFields) !!},
                    model:{
                        field: {!! printString($search->key,$defaultSearchKey) !!},
                        filter: {!! printString($search->filter,'contains') !!},
                        text: {!! printString($search->value) !!},
                    },
                    components:[
                    {
                        name:'el-input',
                        options:{}
                    },
                    {
                        name:'el-date-picker',
                        options:{
                            type:"datetime"
                        }
                    }
                    ]
                }
            },
            methods:{
                getFieldType(){
                    switch (this.fields[this.model.field].type) {
                        case 'color':
                            return 'el-color-picker';
                            break;
                        case 'date':
                            return 'el-date-picker';
                            break;
                        default:
                            return 'el-input'
                            break;
                    }
                }
            }
        });
    </script>
    @endpush
@endonce