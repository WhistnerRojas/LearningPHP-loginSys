const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e)=>{
    e.preventDefault();
}

inputField.focus();
inputField.onkeyup = ()=>{
    if(inputField.value != ""){
        sendBtn.classList.add("active");
    }else{
        sendBtn.classList.remove("active");
    }
}

sendBtn.onclick = async ()=>{
    const msgForm = new FormData(form)
    const url ='http://localhost/signinup/assets/php/sendMsg.php'
    const body = {
        method: 'POST',
        body: msgForm
    }
    return await fetch(url, body)
    .then( response => {
        if(response.status === 200){
            inputField.value = ""
            scrollToBottom()
        }
    })
    .catch( err => console.log(err) )
}

chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}

setInterval( async() =>{
    // window.onload = async() =>{
    return await fetch(`http://localhost/signinup/assets/php/chatSearch.php?user_id=${form[0].value}`,{
        method: 'POST',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response=> response.text())
    .then(data => {
        chatBox.innerHTML = data
        scrollToBottom()
    })
    .catch( err=>{
        console.log(err);
    })
}
, 1000);

function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  