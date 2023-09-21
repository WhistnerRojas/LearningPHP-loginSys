
export default class User{
    constructor(fname = "", lname = "", email, pass, profilePic = ''){
        this.fname= fname
        this.lname = lname
        this.email = email
        this.pass = pass
        this.profilePic = profilePic
    }

    async login(){
        let formdata = new FormData();
        const email = this.email
        const pass = this.pass


        formdata.append("email", email);
        formdata.append("pass", pass);

        if(this.sanitizeEmail() === true){
            return await fetch(`http://localhost/signinup/assets/php/login.php`,{
            method: 'POST',
            body: formdata,
            })
            .then(res =>res.json())
            .then(data =>{
                return data.msg
            })
            .catch(err =>{
                console.log(err)
            })
        }
    }

    sanitizeFName(){
        const namePattern = /^[a-zA-Z]{2,}$/
        // console.log(`this.fname: "${this.fname}"`);
        return namePattern.test(this.fname)
    }

    sanitizeLName(){
        const namePattern = /^[a-zA-Z]{2,}$/
        // console.log(`this.lname: "${this.lname}"`);
        return namePattern.test(this.lname)
    }

    sanitizeEmail(){
        const emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return emailFormat.test(this.email);
    }

    sanitizePass(){
        const hasUppercase = /[A-Z]/.test(this.pass);
        const hasLowercase = /[a-z]/.test(this.pass);
        const hasDigit = /\d/.test(this.pass);
        const hasSpecialChar = /[@$!%*?&]/.test(this.pass);
        const hasCorrectLength = /^.{8,}$/.test(this.pass);
        let errUpper = '', errLower = '', errDigit = '', errSpecial = '', errLegnth = ''

        if (!hasUppercase) {
            errUpper = "<p>*Password must have at least one uppercase letter.</p>";
        }
          
        if (!hasLowercase) {
            errLower="<p>*Password must have at least one lowercase letter.</p>";
        }
        
        if (!hasDigit) {
            errDigit= "<p>*Password must have at least one digit.</p>"
        }
        
        if (!hasSpecialChar) {
            errSpecial= "<p>*Password must have at least one special character.</p>"
        }
        
        if (!hasCorrectLength) {
            errLegnth= "<p>*Password must be 8 characters long or more.</p>"
        }
        
        // To check if all conditions are met:
        if (hasUppercase && hasLowercase && hasDigit && hasSpecialChar && hasCorrectLength) {
            return true;
        }else{
            let errMsg = errUpper + errLower + errDigit + errSpecial + errLegnth;
            return errMsg
        }
    }

    checkProfilePic(){
        const validExtensions = ["jpeg", "png", "jpg"];
        const profilePic = this.profilePic;
        let y = profilePic.toLowerCase().slice(profilePic.lastIndexOf(".") + 1);

        if(!validExtensions.includes(y)){
            console.log('your file should be in image format.')
            return false;
        }
        else {
            return true;
        }
    }

    async regNewUser(){

        if(this.sanitizeFName && this.sanitizeLName && this.sanitizeEmail && this.sanitizePass && this.checkProfilePic){
            console.log('got in')
            const regFormdata = new FormData();
            const fname = this.fname
            const lname = this.lname
            const email = this.email
            const pass = this.pass
            const profilePic = this.profilePic


            regFormdata.append("fname", fname);
            regFormdata.append("lname", lname);
            regFormdata.append("email", email);
            regFormdata.append("pass", pass);
            regFormdata.append("profilepic", profilePic);

            if(this.sanitizeEmail() === true){
                return await fetch(`http://localhost/signinup/assets/php/register.php`,{
                method: 'POST',
                body: regFormdata,
                })
                .then(res =>res.json())
                .then(data =>{
                    return data.msg
                })
                .catch(err =>{
                    console.log(err)
                    return err;
                })
            }
        }else{
            return 'Something went wrong. Please try again.';
        }
    }

}
