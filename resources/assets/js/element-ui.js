import { 
    Col,Row,
    Form,FormItem,Input,Button,
    Table,TableColumn,
    Card,Dialog,Collapse, CollapseItem,
    Loading,Notification } from 'element-ui';
Vue.use(Col)
Vue.use(Row)
Vue.use(Form)
Vue.use(FormItem)
Vue.use(Input)
Vue.use(Button)
Vue.use(Table)
Vue.use(TableColumn)
Vue.use(Card)
Vue.use(Dialog)
Vue.use(Collapse)
Vue.use(CollapseItem)
Vue.use(Loading.directive);
Vue.prototype.$loading = Loading.service;
Vue.prototype.$notify = Notification
