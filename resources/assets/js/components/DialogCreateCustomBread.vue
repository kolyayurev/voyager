<template>
  <el-dialog
    class="dialog dialog-create-custom-bread modal-info"
    :title="title"
    top="10vh"
    :visible.sync="dialogVisibility"
		@close="resetForm">
		<el-form :model="model" :rules="rules" ref="ruleForm" label-width="120px" :label-position="labelPosition" class="el-dialog__body-inner">
        <form 
            :action="url" 
            ref="form"
            method="POST"
            >
          <slot name="field"></slot>
          <el-form-item label="URL slug" prop="slug">
            <el-input v-model="model.slug" name="slug" placeholder="page-custom"></el-input>
          </el-form-item>
          <el-form-item label="Модель" prop="model_name">
            <el-input v-model="model.model_name" name="model_name" placeholder="App\Models\Page"></el-input>
          </el-form-item>
          <div class="text-right">
              <button class="btn btn-primary" @click.prevent="submitForm">{{$t('buttons.save')}}</button>
          </div>
        </form>
		</el-form>
  </el-dialog>
</template>

<script>
import {dialog} from '../mixins/dialog'

export default {
    mixins: [dialog],
		props:{
			title: {
					type: String,
					default: '',
			},
      url: {
					type: String,
					default: '',
			},
		},
		data(){
			return{
				labelPosition: 'top',
				model:{
					slug:'',
					model_name:'',
				},
				rules: {
					slug: [
						{ required: true, message: this.$t('validation.required'), trigger: 'change' },
						{ max: 255, message: this.$t('validation.max_length_exceeded'), trigger: 'change' }
					],
          model_name: [
						{ required: true, message: this.$t('validation.required'), trigger: 'change' },
						{ max: 255, message: this.$t('validation.max_length_exceeded'), trigger: 'change' }
					],
				},
			}
		},
		methods: {
      submitForm() {
        this.$refs.ruleForm.validate((valid) => {
          if (valid) {
            this.$refs.form.submit()
          } else {
            return false;
          }
        });
      },
      resetForm() {
        this.$refs.ruleForm.resetFields();
        for (var key in this.model) {
            this.model[key] = null;
        }
      },
    }
}
</script>

<style>

</style>