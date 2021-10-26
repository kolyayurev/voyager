<template>
  <el-dialog
    class="dialog dialog-media-picker modal-info"
    title="Media Picker"
    top="10vh"
    :visible.sync="dialogVisibility"
	>
    <media-manager
      ref="media_manager"
      :base-path="options.basePath"
      :filename="options.filename"
      :allow-multi-select="options.allowMultiSelect"
      :max-selected-files="options.maxSelectedFiles"
      :min-selected-files="options.minSelectedFiles"
      :show-folders="options.showFolders"
      :show-toolbar="options.showToolbar"
      :allow-upload="options.allowUpload"
      :allow-move="options.allowMove"
      :allow-delete="options.allowDelete"
      :allow-create-folder="options.allowCreateFolder"
      :allow-rename="options.allowRename"
      :allow-crop="options.allowCrop"
      :allowed-types="options.allowedTypes"
      :accept="options.accept"
      :pre-select="options.preSelect"
      :expanded="options.expanded"
      :show-expand-button="options.showExpandButton"
      :element="options.element"
      :details="options.details"
      v-model="localValue"
    ></media-manager>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="closeDialog">{{ $t('buttons.ok') }}</el-button>
    </span>
  </el-dialog>
</template>

<script>

import vModel from '../mixins/v-model';
import {dialog} from '../mixins/dialog'

export default {
	mixins: [dialog,vModel],
	name: 'v-dialog-media-picker',
	props:{
	},
	data(){
		return{
			options:{},
		}
	},
	created(){
		this.setDefaultOptions();
	},
	methods:{
    getSelectedFilesData(){
      return this.$refs.media_manager?this.$refs.media_manager.getSelectedFilesData():[];
    },
    init(options){
      this.setOptions(options).openDialog();
      // this.$refs.media_manager.init();
    },
		setOptions(options){
      this.options = {...this.options,...options};
      return this;
    },
    setContent(content){
      this.content = content;
      return this;
    },
    setDefaultOptions(){
      this.options = {
        basePath:'/',
        filename:null,
        allowMultiSelect: false,
        maxSelectedFiles:0,
        minSelectedFiles:0,
        showFolders:false,
        showToolbar:true,
        allowUpload:true,
        allowMove: false,
        allowDelete: true,
        allowCreateFolder: true,
        allowRename: true,
        allowCrop: true,
        allowedTypes: [],
        accept: '',
        preSelect:false,
        expanded: false,
        showExpandButton: true,
        element: '',
        details: {},
      }
    }
	}
}
</script>

<style lang="scss">


</style>