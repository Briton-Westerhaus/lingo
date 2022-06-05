let selectedInput;
let changeNumLetters = false;

function selectInput(inputNumber) {
    selectedInput = inputNumber;
    let input = document.getElementById("input" + inputNumber);
    if (!!input) {
        input.focus();
        input.select();
    }
    }

function inputChanged(evt) {
    if (evt.value.length == 1 && selectedInput < numLetters - 1) {
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
    let lettersGuessed = 0;
    const form = document.forms["gameForm"];

    if (!!form["playAgain"]) {
        return true;

    } else {

        for (let i = 0; i < numLetters; i++) {
            value = form[i.toString()].value;
            if (value != undefined && value != "&nbsp;" && value != " " && value != "")
                lettersGuessed++;
        }

        if (lettersGuessed < numLetters)
            return confirm("You've only guessed " + lettersGuessed + " letter" + (lettersGuessed == 1 ? "" : "s") + ". \n\nAre you sure this is your guess?");

    }

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
        case 9: //tab
            if (selectedInput < numLetters - 1) {
                selectInput(selectedInput + 1);
            }
            break;

        case 8: //backspace
            let input = document.getElementById("input" + selectedInput);
            if (!input.value && selectedInput > 0) {
                selectInput(selectedInput - 1);
            }
            break;    

        default:
            break;
    }
    
}