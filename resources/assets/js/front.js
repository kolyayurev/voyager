import Cookies from 'js-cookie'
window.Cookies = Cookies

var adminControlsSwitch = document.getElementById('adminControlsSwitch');
if(adminControlsSwitch)
    adminControlsSwitch.addEventListener("click",function(e){
        var buttons = document.getElementsByClassName('admin-controls__buttons')[0];
        buttons.classList.contains('open')?Cookies.set('admin-controls-expanded',''):Cookies.set('admin-controls-expanded','open');
        console.log(buttons.classList.contains('open')?'y':'no');
        buttons.classList.toggle("open");
        this.classList.toggle("open");
    },false);
