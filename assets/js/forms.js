import User from './classes/User.js' 

const formSingUp = document.querySelectorAll(".signup form"),
formLogIn = document.querySelectorAll(".login form"),
continueBtn = document.querySelector(".button input"),
errorText = document.querySelector(".error-text");

Array.from(formSingUp).forEach(registerInput => {
    registerInput.addEventListener('submit', event =>{
        event.preventDefault()
    })
})

Array.from(formLogIn).forEach(logInInput => {
    
    logInInput.addEventListener('submit', event =>{
        event.preventDefault()
        const email = logInInput[0].value;
        const password =logInInput[1].value;
        const logUser = new User(undefined, undefined, email, password)

        if(!logUser.sanitizeEmail()){
            errorText.style.display = "block";
            errorText.textContent = "invalid email";
        }else{
            logUser.login() === 'invalid' && (errorText.style.display = "block", errorText.textContent = "invalid email") 
        }
    })
    
})