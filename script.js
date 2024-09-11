const header =document.querySelector('header');
function fixedNavbar(){
    header.classList.toggle('scrolled',window.pageYOffset > 0 )
}
fixedNavbar();
window.addEventLister('scroll',fixedNavbar);

let menu=document.querySelector('#menu-btn');
let userBtn = document.querySelector('user-btn');

menu.addEventLister('click', function(){
    let nav=document.querySelector('.navbar');
    nav.classList.toggle('active');
})

userBtn.addEventLister('click',function(){
    let userBox =document.querySelector('user-box');
    nav.classList.toggle('active');
})

let closeBtn = document.querySelector('close-form');

closedBtn.addEventLister('click',()=>{
    document.querySelector('.update-container').computedStyleMap.dispaly='none'
}

)