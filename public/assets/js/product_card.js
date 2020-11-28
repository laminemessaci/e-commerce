document.addEventListener("DOMContentLoaded", function(event) {


    const cartButtons = document.querySelectorAll('.cart-button');

    cartButtons.forEach(button => {

        button.addEventListener('click',cartClick);

    });

    function cartClick(){
        let button =this;
        button.classList.add('clicked');
    }

});

function change_image(image){

    var container = document.getElementById("main-image");

    container.src = image.src;
}



document.addEventListener("DOMContentLoaded", function(event) {


});