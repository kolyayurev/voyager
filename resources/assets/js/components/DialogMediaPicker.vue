<template>
  <el-dialog
    class="dialog dialog-media-picker modal-info"
    title="Media Picker"
    top="10vh"
    :visible.sync="dialogVisibility"
	>
  <!-- <media-manager
          ref="media_manager"
          base-path="{{ $options->base_path ?? '/'.$dataType->slug.'/' }}"
          filename="{{ $options->rename ?? 'null' }}"
          :allow-multi-select="{{ isset($options->max) && $options->max > 1 ? 'true' : 'false' }}"
          :max-selected-files="{{ $options->max ?? 0 }}"
          :min-selected-files="{{ $options->min ?? 0 }}"
          :show-folders="{{ var_export($options->show_folders ?? false, true) }}"
          :show-toolbar="{{ var_export($options->show_toolbar ?? true, true) }}"
          :allow-upload="{{ var_export($options->allow_upload ?? true, true) }}"
          :allow-move="{{ var_export($options->allow_move ?? false, true) }}"
          :allow-delete="{{ var_export($options->allow_delete ?? true, true) }}"
          :allow-create-folder="{{ var_export($options->allow_create_folder ?? true, true) }}"
          :allow-rename="{{ var_export($options->allow_rename ?? true, true) }}"
          :allow-crop="{{ var_export($options->allow_crop ?? true, true) }}"
          :allowed-types="{{ isset($options->allowed) && is_array($options->allowed) ? json_encode($options->allowed) : '[]' }}"
          :pre-select="false"
          :expanded="{{ var_export($options->expanded ?? false, true) }}"
          :show-expand-button="true"
          :element="'input[name=&quot;{{ $row->field }}&quot;]'"
          :details="{{ json_encode($options ?? new class{}) }}"
          v-model="content"
  ></media-manager> -->
  <span slot="footer" class="dialog-footer">
    <el-button type="primary" @click="closeDialog">{{ $t('buttons.cancel') }}</el-button>
  </span>
  </el-dialog>
</template>

<script>
import {dialog} from '../mixins/dialog'

export default {
	mixins: [dialog],
	name: 'v-dialog-media-picker',
	props:{
		
	},
	data(){
		return{
			options:{}
		}
	},
	created(){
		this.setDefaultOptions()
	},
	methods:{
		setOptions(){

    },
    setDefaultOptions(){
      this.options = {
        dd:1
      }
    }
	}
}
</script>

<style lang="scss">


</style>