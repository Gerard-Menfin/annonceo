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

//console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


/* Fonction pour  l'affichage de l'image juste après le téléchargement depuis un input file */
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

/* Fonctions pour les sliders Bulma */

function findOutputForSlider( element ) {
    var idVal = element.id;
    var outputs = document.getElementsByTagName( 'output' );
    for( var i = 0; i < outputs.length; i++ ) {
    if ( outputs[ i ].htmlFor == idVal )
        return outputs[ i ];
    }
}

function getSliderOutputPosition( slider ) {
// Update output position
var newPlace,
    minValue;

var style = window.getComputedStyle( slider, null );
// Measure width of range input
sliderWidth = parseInt( style.getPropertyValue( 'width' ), 10 );

// Figure out placement percentage between left and right of input
if ( !slider.getAttribute( 'min' ) ) {
    minValue = 0;
} else {
    minValue = slider.getAttribute( 'min' );
}
var newPoint = ( slider.value - minValue ) / ( slider.getAttribute( 'max' ) - minValue );

// Prevent bubble from going beyond left or right (unsupported browsers)
if ( newPoint < 0 ) {
    newPlace = 0;
} else if ( newPoint > 1 ) {
    newPlace = sliderWidth;
} else {
    newPlace = sliderWidth * newPoint;
}

return {
    'position': newPlace + 'px'
}
}


window.addEventListener("load", function() {
    /* affichage image après téléchargement */
    if(document.querySelector("[id ^= 'annonce_photo']")){
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
    }
    /* fin (affichage image) */

})

/* Bulma - Slider */
// Find output DOM associated to the DOM element passed as parameter
document.addEventListener( 'DOMContentLoaded', function () {
    console.log("DOMContentLoaded");
    // Get all document sliders
    var sliders = document.querySelectorAll( 'input[type="range"].slider' );
    [].forEach.call( sliders, function ( slider ) {
        var output = findOutputForSlider( slider );
        if ( output ) {
        if ( slider.classList.contains( 'has-output-tooltip' ) ) {
            // Get new output position
            var newPosition = getSliderOutputPosition( slider );
    
            // Set output position
            output.style[ 'left' ] = newPosition.position;
        }
    
        // Add event listener to update output when slider value change
        slider.addEventListener( 'input', function( event ) {
            if ( event.target.classList.contains( 'has-output-tooltip' ) ) {
            // Get new output position
            var newPosition = getSliderOutputPosition( event.target );
    
            // Set output position
            output.style[ 'left' ] = newPosition.position;
            }
    
            // Update output with slider value
            output.value = event.target.value;
        } );
        }
    } );
});
    