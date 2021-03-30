import {messages} from './messages'

export default{
    mixins: [messages],
    data(){
        return{
            prLoading: true,
            // errors: [],
        }
    },
    mounted(){
        this.prLoading = false
        // this.displayErrors()
    },
    methods:{
        startLoading(){
            this.prLoading = true
        },
        stopLoading(){
            this.prLoading = false
        },
        checkUndefined(field){
            return typeof field === 'undefined'
        },
        // async displayErrors(){
        //     if(this.errors.length)
        //     {
        //         this.errors.forEach((element,index) => {
        //             setTimeout(() => { this.warningMsg('Внимание',element,2000*(index+1),0); }, 500);
                    
        //         });
        //     }
        // },
        test(callback){
            callback()
        },
        baseAxios(url,data,
            successCallback     = (r) => { this.successMsg(); },
            errorCallback       = (r) => { this.errorMsg('Не удалось',r.data.message);},
            defaultCallback     = (r) => { this.warningMsg(); },
            catchCallback       = (e) => { console.log(e); this.errorMsg(); },
            finallyCallback     = (r) => {}
        ){
            this.startLoading()

            axios
                .post(url, data)
                .then(response => {
                    switch (response.data.status) {
                        case 'success':
                            successCallback(response)
                            break;
                        case 'error':
                            errorCallback(response)
                            break;
                        default:
                            defaultCallback(response)
                    }
                })
                .catch(error => {
                    catchCallback(error)
                })
                .finally(res =>  {
                    this.stopLoading()
                    finallyCallback()
                }) 
        },
        isJsonValid: function(str) {
            try {
                JSON.parse(str);
            } catch (ex) {
                return false;
            }
            return true;
        },
    }
}
  