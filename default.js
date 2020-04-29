let selectedInput;

function selectInput(inputNumber) {
    selectedInput = inputNumber;
    let input = document.getElementById("input" + inputNumber);
    input.focus();
    input.select();
}

function inputChanged(evt) {
    if (evt.value.length == 1) {
        selectInput(selectedInput + 1);
    }
}

function showModal() {
    document.getElementById("ModalContainer").style.display = "block";  
}

function hideModal() {
    document.getElementById("ModalContainer").style.display = "None";
}

function validateForm() {
    let value;
    let numLetters = 0;
    const form = document.forms["gameForm"];

    if (!!form["playAgain"])
        return true;

    for (var i = 0; i < 5; i++) {
        value = form[i.toString()].value;
        if (value != undefined && value != "&nbsp;" && value != " " && value != "")
            numLetters++;
    }

    if (numLetters < 5)
        return confirm("You've only guessed " + numLetters + " letter" + (numLetters == 1 ? "" : "s") + ". \n\nAre you sure this is your guess?");

    return true;
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