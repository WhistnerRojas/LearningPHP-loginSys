import User from './classes/User.js' 

const formSignUp = document.querySelectorAll(".signup form"),
formLogIn = document.querySelectorAll(".login form"),
continueBtn = document.querySelector(".button input")
let errorText = document.querySelector(".error-text")

Array.from(formSignUp).forEach(registerInput => {

    let fname = registerInput[0].value,
            lname = registerInput[1].value,
            email = registerInput[2].value,
            pass = registerInput[3].value,
            profilePic = registerInput[4].files[0];
    let register = new User(fname, lname, email, pass, profilePic);

    registerInput[0].addEventListener('keyup', event => {
        setTimeout(() => {
            register.fname = registerInput[0].value
            if (!register.sanitizeFName()) {
            errorText.style.display = "block";
            errorText.innerHTML = "<span>invalid first name.</span>";
            } else{
                errorText.style.display = "none";
            }
        }, 2000);
        });

    registerInput[1].addEventListener('keyup', event => {
        setTimeout(() => {
            register.lname = registerInput[1].value
            if (!register.sanitizeLName()) {
            errorText.style.display = "block";
            errorText.innerHTML = "<span>invalid last name.</span>";
            } else{
                errorText.style.display = "none";
            }
        }
        , 2000)
        });

    registerInput[2].addEventListener('keyup', event => {
        setTimeout(() => {
            register.email = registerInput[2].value
            if (!register.sanitizeEmail()) {
                errorText.style.display = "block";
                errorText.innerHTML = "<span>invalid Email.</span>";
            } else{
                errorText.style.display = "none";
            }
        }
        , 3000)
        });

    registerInput[3].addEventListener('keyup', event => {
        setTimeout(() => {
            register.pass = registerInput[3].value
            if (typeof register.sanitizePass() === "string") {
                errorText.style.display = "block";
                errorText.innerHTML = "<span class='pass'>"+register.sanitizePass()+"</span>";
            } else{
                errorText.style.display = "none";
            }
        }
        , 3000)
    });

    registerInput[4].addEventListener('change', event => {
        register.profilePic = event.target.files[0]

            console.log(registerInput[4].files[0]);

        if (register.checkProfilePic() !== true) {
            errorText.style.display = "block";
            errorText.innerHTML = "<span>Your profile picture should be in an image format.</span>";
        } else{
            errorText.style.display = "none";
        }
    });

    registerInput.addEventListener( 'submit', event =>{
        event.preventDefault()
        register.regNewUser().then(result => {
            errorText.style.display = "block"
            errorText.innerHTML = result.msg + " redirecting...";
            setTimeout(() => {
                if(result.msg2 === 'valid'){
                    window.location.href = "http://localhost/signinup/"
                }
            }, 2000)
        })
    })
})

Array.from(formLogIn).forEach(logInInput => {
    
    logInInput.addEventListener('submit', event =>{
        event.preventDefault()
        const email = logInInput[0].value,
            password =logInInput[1].value,     
            logUser = new User(undefined, undefined, email, password)

        if(logUser.sanitizeEmail() !== true){
            errorText.style.display = "block"
            errorText.textContent = "invalid email"
        }else{
            logUser.login().then(result => {
                result === 'invalid' && (errorText.style.display = "block", errorText.textContent = "invalid email or password")
                result === 'valid' && (window.location.href = "http://localhost/signinup/")
            }).catch(error => {
                errorText.textContent = error
            })
        }
    })
    
})