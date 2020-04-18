let selectedInput;

function selectInput(inputNumber) {
    selectedInput = inputNumber;
    let input = document.getElementById("input" + inputNumber);
    input.focus();
    input.select();
}

window.onload = function() {
    selectedInput = 0;
    this.selectInput(selectedInput);
}

document.onkeydown = function(evt) {
    switch(evt.keyCode) {
        case 37: //left arrow
            if (selectedInput > 0) {
                selectInput(selectedInput - 1);
            }
            break;
        
        case 39: //right arrow
            if (selectedInput < 5) {
                selectInput(selectedInput + 1);
            }
            break;

        default:
            break;
    }
    
}

document.onkeypress = function(evt) {
    if (evt.keyCode == 9 && selectedInput < 5) {
        selectInput(selectedInput + 1);
    } 
}