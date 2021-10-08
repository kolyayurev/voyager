<form  method="get" class="form-search" id="search"  @keyup.enter="$el.submit()">
    <el-row :gutter="10">
        <el-col :sm="12" :md="4">
            <el-select v-model="model.field" @change="handleFieldChange" filterable>
                <el-option v-for="(item,index) in fields" :key="index" :value="index" :label="item.name"   >
                </el-option>
            </el-select>
        </el-col>
        <el-col :sm="12" :md="4">
            <el-select v-model="model.filter">
                <el-option v-for="(filter,index) in filters" :key="index" :value="index"  :label="filter"></el-option>
            </el-select>
        </el-col>
        <el-col :xs="18" :sm="18" :md="14">
            {{-- --}}
            <component :is="getFieldType().component"  v-bind="getFieldType().options"   placeholder="{{ __('voyager::generic.search') }}" v-model="model.text" clearable></component>
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
                    filters:{
                        contains:'contains',
                        equals:'=',
                        less:'>=',
                        greater:'<=',
                    },
                    components:{
                        'text':{
                            component:'el-input',
                            options:{}
                        },
                        'color':{
                            component:'el-color-picker',
                            options:{}
                        },
                        'date':{
                            component:'el-date-picker',
                            options:{
                                valueFormat:'yyyy-MM-dd'
                            }
                        },
                        'timestamp':{
                            component:'el-date-picker',
                            options:{
                                type:"datetime",
                                valueFormat:'yyyy-MM-dd HH:mm:ss'
                            }
                        },
                        'time':{
                            component:'el-date-picker',
                            options:{
                                type:"datetime",
                                valueFormat:'yyyy-MM-dd HH:mm:ss'
                            }
                        },
                    }
                }
            },
            methods:{
                getFieldType(){
                    if (this.model.field && this.fields[this.model.field].type in this.components){
                        return this.components[this.fields[this.model.field].type];
                    }
                    else
                        return this.components['text'];
                },
                handleFieldChange(){
                    this.model.text = null
                }
            }
        });
    </script>
    @endpush
@endonce