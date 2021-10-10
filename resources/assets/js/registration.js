import Vue from 'vue';
window.Vue = Vue;
import jQuery from 'jquery';
window.jQuery = jQuery;
window.$ = jQuery;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


Vue.component('draggable',require('vuedraggable').default);
Vue.component('editor',require('@tinymce/tinymce-vue').default);
Vue.component('admin-menu', require('./components/admin_menu.vue').default);
import DialogMediaPicker from './components/DialogMediaPicker'
Vue.component('v-dialog-media-picker',DialogMediaPicker);
import DialogCreateCustomBread from './components/DialogCreateCustomBread'
Vue.component('v-dialog-create-custom-bread',DialogCreateCustomBread);

// import BrowseServerSideSearch from './components/BrowseServerSideSearch'
// Vue.component('v-browse-server-side-search',BrowseServerSideSearch);

var base  = require('./mixins/base');

Vue.mixin(base.default);

require('./element-ui');
require('./lang');

window.perfectScrollbar = require('perfect-scrollbar/jquery')($);
window.Cropper = require('cropperjs');
window.Cropper = 'default' in window.Cropper ? window.Cropper['default'] : window.Cropper;
window.toastr = require('toastr');
window.DataTable = require('datatables.net-bs');
require( 'datatables.net-fixedcolumns-bs');     

require('dropzone');
require('jquery-match-height');
require('jquery-ui');
require('./jquery.ui.timepicker');
require('nestable2');
require('select2');

require('bootstrap');
require('bootstrap-toggle');
require('bootstrap-switch');
require('eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker');

// Editors
var brace = require('brace');
require('brace/mode/json');
require('brace/theme/github');

window.EasyMDE = require('easymde');

window.TinyMCE = window.tinymce = require('tinymce');
require('./voyager_tinymce');
window.voyagerTinyMCE = require('./voyager_tinymce_config');

require('./voyager_ace_editor');

require('./slugify');
require('./multilingual');
window.helpers = require('./helpers.js');