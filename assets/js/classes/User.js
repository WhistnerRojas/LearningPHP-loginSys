
export default class User{
    constructor(fname = "", lname = "", email, pass){
        this.fname= fname
        this.lname = lname
        this.email = email
        this .pass = pass
    }

    sanitizeFname(){
        return this.fname
    }

    sanitizaLname(){
        return this.lname
    }

    sanitizeEmail(){
        const emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        // const emailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailFormat.test(this.email);
    }

    async login(){
        var formdata = new FormData();
        const email = this.email
        const pass = this.pass
        formdata.append("email", email);
        formdata.append("pass", pass);
        return await fetch(`http://localhost/signinup/assets/php/login.php`,{
            method: 'POST',
            body: formdata,
        })
        .then(res =>res.json())
        .then(data =>{
            return data
        })
        .catch(err =>{
            console.log(err)
        })
    }

}
