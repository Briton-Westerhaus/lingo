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
    let lettersGuessed = 0;
    const form = document.forms["gameForm"];

    if (!!form["playAgain"]) {
        return true;

    } else if (form["changeNumLetters"]) {
        return confirm("Are you sure? This will end your current game.")
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
            if (selectedInput < numLetters) {
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