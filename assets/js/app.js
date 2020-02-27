/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            var idphoto = input.getAttribute("id") + "_img";
            console.log(idphoto); 
            document.querySelector("#" + idphoto).setAttribute('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

window.addEventListener("load", function() {
    
    document.querySelector("#annonce_photo1").addEventListener("change", function(){
        readURL(this);
    });
    document.querySelector("#annonce_photo2").addEventListener("change", function(){
        readURL(this);
    });
    document.querySelector("#annonce_photo3").addEventListener("change", function(){
        readURL(this);
    });
    document.querySelector("#annonce_photo4").addEventListener("change", function(){
        readURL(this);
    });
    document.querySelector("#annonce_photo5").addEventListener("change", function(){
        readURL(this);
    });
})
