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
            <component :is="componentName"  v-bind="componentOptions"   placeholder="{{ __('voyager::generic.search') }}" 
                        v-model="model.text" clearable
                        v-on:focus="isRelationField ? handleRelationSearch() : null"
                        :remote-method="isRelationField ? handleRelationSearch : null"
                        >
              <template v-if="['select_dropdown','select_multiple','radio_btn'].includes(fieldType) && fieldDetails.options">
                <el-option
                    v-for="(item,key) in fieldDetails.options"
                    :key="key"
                    :label="item"
                    :value="key">
                </el-option>
              </template>
              <template v-if="['checkbox'].includes(fieldType)">
                <el-option  :label="fieldDetails.off??'@lang('voyager::generic.no')'" :value="0"></el-option>
                <el-option  :label="fieldDetails.on??'@lang('voyager::generic.yes')'" :value="1"></el-option>
              </template>
              <template v-if="['belongsTo'].includes(fieldType)">
                <el-option
                    v-for="item in relationData"
                    :key="item.id"
                    :label="item.text"
                    :value="item.id">
                </el-option>
              </template>
            
            </component>
        </el-col>
        <el-col :xs="6"  :sm="6" :md="2">
            <el-button class="form-search__btn-submit"  icon="el-icon-search" @click="$el.submit()"></el-button>
        </el-col>
    </el-row>
    <input type="hidden" v-model="model.field"  name="key">
    <input type="hidden" v-model="model.filter"  name="filter">
    <input type="hidden" :value="printValue(model.text)"  name="s">
    
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
                        text: {!! printByField($search->type,$search->value) !!},
                    },
                    filters:{
                        contains:'contains',
                        equals:'=',
                        not:'!=',
                        less:'>=',
                        greater:'<=',
                    },
                    relationData:[],
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
                        'select_dropdown':{
                            component:'el-select',
                        },
                        'select_multiple':{
                            component:'el-select',
                            options: {
                                multiple: true
                            }
                        },
                        'checkbox':{
                            component:'el-select',
                            options:{}
                        },
                        'radio_btn':{
                            component:'el-select',
                        },
                        'belongsTo':{
                            component:'el-select',
                            options:{
                                filterable:true,
                                remote:true,
                            }
                        }
                    }
                }
            },
            computed:{
                isRelationField(){
                    return this.fieldType == 'belongsTo' ? true : false;
                },
                fieldType(){
                    return this.currentField ? this.currentField.type : 'text';
                },
                fieldDetails(){
                    if (this.currentField){
                        return this.currentField.details;
                    }
                },
                currentField(){
                    return this.model.field ? this.fields[this.model.field] : false;
                },
                componentName(){
                    return this.getComponent().component;
                },
                componentOptions(){
                    return this.getComponent().options??{};
                },
            },
            beforeMount(){
                if(this.isRelationField)
                    this.handleRelationSearch();
            },
            methods:{
                getComponent(){
                    let type = this.fieldType
                    return  this.components[this.model.field && type in this.components ? type : 'text'];
                },
                printValue(value){
                    if(Array.isArray(value))
                        return JSON.stringify(value);
                    if(typeof value == "boolean")
                        return value ? 1 : 0;

                    return value;
                },
                handleFieldChange(){
                    switch (this.fieldType) {
                        case 'checkbox':
                            this.model.text = 0
                            break;
                        case 'belongsTo':
                            this.handleRelationSearch();
                        default:
                            this.model.text = null
                            break;
                    }
                },
                handleRelationSearch(query = null){
                    var _this = this;
                    var url = '{{ route('voyager.' . $dataType->slug.'.relation') }}';
                    var config = {
                        params:{ 
                            search: query,
                            type: this.fieldDetails.has_relation.field, 
                            page: 1,
                            on_page: 1000,
                            method: 'browse'
                        }
                    };
                    axios
                        .get(url, config)
                        .then(response => {
                            console.log(response)
                            _this.relationData = response.data.results
                        });
                  
                }
            }
        });
    </script>
    @endpush
@endonce