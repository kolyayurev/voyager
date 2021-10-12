@php
    $allowMultiSelect = isset($options->max) && $options->max > 1 ? true : false;
@endphp
<div id="media_picker_{{ $row->field }}" class="media-picker-formfield">
    <div class="media-picker-formfield__wrap">
        <ul class="media-picker-formfield__items" >
            <li v-for="file in getFiles()" :key="file" class="media-picker-formfield__item" v-on:dblclick="openFile(file)">
                <div class="media-picker-formfield__item-icon">
                    <template v-if="fileIs(file, 'image')">
                        <div class="media-picker-formfield__item-img" :style="imgIcon(storagePath+file)"></div>
                    </template>
                    <template v-else-if="fileIs(file, 'video')">
                        <i class="icon voyager-video"></i>
                    </template>
                    <template v-else-if="fileIs(file, 'audio')">
                        <i class="icon voyager-music"></i>
                    </template>
                    <template v-else-if="fileIs(file, 'zip')">
                        <i class="icon voyager-archive"></i>
                    </template>
                    <template v-else-if="fileIs(file, 'folder')">
                        <i class="icon voyager-folder"></i>
                    </template>
                    <template v-else>
                        <i class="icon voyager-file-text"></i>
                    </template>
                </div>
                <div class="media-picker-formfield__item-details">
                    <div class="media-picker-formfield__item-title">@{{ getFileName(file) }}</div>
                </div>
            </li>
            <div class="media-picker-formfield__empty" v-if="!content || !content.length">
                @lang('voyager::media.empty')
            </div>
        </ul>
        <div class="media-picker-formfield__btn-box">
            <button class="media-picker-formfield__button btn" type="button" @click="openMediaPicker">@lang('voyager::generic.modify')</button>
        </div>
    </div>
    <input type="hidden" name="{{ $row->field }}" :value="{{ is_array($content)? json_encode(printArray($content)) : $content }}">
    <v-dialog-media-picker v-model="content" ref="dialog"></v-dialog-media-picker>
    <!-- Image Modal -->
    <el-dialog
        v-if="selected_file && fileIs(selected_file, 'image')"
        class="dialog modal-info"
        top="10vh"
        :title="getFileName(selected_file)"
        :visible.sync="dialogImageShow"
        >
        <img :src="storagePath+selected_file" class="img img-responsive" style="margin: 0 auto;">
    </el-dialog>
    <!-- End Image Modal -->
</div>
@push('javascript')
<script>
var media_picker_{{ $row->field }} = new Vue({
    el: '#media_picker_{{ $row->field }}',
    data(){
        return{
            options:{
                basePath:{!! printString($options->base_path ?? '/'.$dataType->slug.'/') !!},
                filename:{!! isset($options->rename) ? printString($options->rename) : 'null' !!},
                allowMultiSelect: {{ printBool($allowMultiSelect) }},
                maxSelectedFiles: {{ printInt($options->max ?? 0) }},
                minSelectedFiles: {{ printInt($options->min ?? 0) }},
                showFolders: {{ printBool($options->show_folders ?? false) }},
                showToolbar: {{ printBool($options->show_toolbar ?? true) }},
                allowUpload: {{ printBool($options->allow_upload ?? true) }},
                allowMove: {{ printBool($options->allow_move ?? false) }},
                allowDelete: {{ printBool($options->allow_delete ?? true) }},
                allowCreateFolder: {{ printBool($options->allow_create_folder ?? true) }},
                allowRename: {{ printBool($options->allow_rename ?? true) }},
                allowCrop: {{ printBool($options->allow_crop ?? true) }},
                allowedTypes: {!! printArray(isset($options->allowed) && is_array($options->allowed) ? $options->allowed : []) !!},
                accept: {!! printString($options->accept??'') !!},
                preSelect: false,
                expanded: {{ printBool($options->expanded ?? false) }},
                showExpandButton: true,
                element: 'input[name="{{ $row->field }}"]',
                details: {!! printObject($options ?? new class{}) !!},
            },
            dialogImageShow: false,
            content: {!! is_array($content)? printArray($content) : $content !!},
            selected_file: null,
            storagePath: {!! printString(Str::finish(Storage::disk(config('voyager.storage.disk'))->url('/'), '/')) !!},
        }
    },
    methods:{
        openMediaPicker(){
            this.$refs.dialog.init(this.options);
        },
        openFile(file){
            if(this.fileIs(file,'image')){
                this.selected_file = file;
                this.dialogImageShow = true;
            }
        },
        getFiles: function() {
            if (!this.options.allowMultiSelect) {
                var content = [];
                if (this.content != '') {
                    content.push(this.content);
                }
                return content;
            } else {
                return this.content;
            }
        },
        fileIs: function(file, type) {
            if (typeof file === 'string') {
                if (type == 'image') {
                    return this.endsWithAny(['jpg', 'jpeg', 'png', 'bmp', 'svg'], file.toLowerCase());
                }
            } else {
                return file.type.includes(type);
            }

            return false;
        },
        endsWithAny: function(suffixes, string) {
            return suffixes.some(function (suffix) {
                return string.endsWith(suffix);
            });
        },
        imgIcon: function(path) {
            path = path.replace(/\\/g,"/");
            return 'background-size: cover; background-image: url("' + path + '"); background-repeat:no-repeat; background-position:center center;display:inline-block; width:100%; height:100%;';
        },
        getFileName: function(name) {
            var name = name.split('/');
            return name[name.length -1];
        },
    }
});
</script>
@endpush
    