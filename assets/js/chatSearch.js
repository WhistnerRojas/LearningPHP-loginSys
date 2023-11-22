const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list"),
LoadSpinner = document.querySelector("#spinner");

searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}
// searchBar.addEventListener('keypress', () =>{
//   usersList.innerHTML = '<div class="loading-spinner" style="display:block;"></div>'
// })
searchBar.onkeyup = ()=>{
  usersList.innerHTML = '<div class="loading-spinner" style="display:block;"></div>'
  let searchTerm = searchBar.value;
  if(searchTerm != ""){
    searchBar.classList.add("active");
  }else{
    searchBar.classList.remove("active");
  }

  let formdata = new FormData();
  formdata.append("search", searchTerm);

  setTimeout(()=>{
    search(formdata)
  }, 1000)

}

async function search(formdata){
  return(
    await fetch(`http://localhost/signinup/assets/php/chatSearch.php/`,{
            method: 'POST',
            body: formdata,
        })
        .then(res =>res.text())
        .then(data =>{
          usersList.innerHTML = data;
        })
        .catch(err =>{
            console.log(err)
        })
  )
}

async function getChatList(){
  return await fetch(`http://localhost/signinup/assets/php/chatSearch.php/?users=online`,{
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response=> response.text())
  .then(data => {
    if(!searchBar.classList.contains("active")){
      usersList.innerHTML = data;
    }
  })
  .catch( err=>{
    console.log(err);
  })
}

window.onload = async () =>{
  LoadSpinner.style.display = 'block';
  setInterval(async () =>{
  if(getChatList() == '' || null){
    LoadSpinner.style.display = 'none';
    getChatList()
  }else{
    LoadSpinner.style.display = 'block';
  }
}, 1000);
}

function listData(data){
  
  for(let i=0; i<data.rows; i++){
    const chatLink = document.createElement("a");
    const content = document.createElement("div");
    const usersPic = document.createElement("img");
    const usersInfo = document.createElement("div");
    const usersName = document.createElement("span");
    const newMsg = document.createElement("p");
    const userStatus = document.createElement("div");

    //set the link for the user chat message
    usersList.appendChild(chatLink);
    chatLink.setAttribute("href", `http://localhost/signinup/?user=chat&user_id=${data.users[i].unique_id}`)
    chatLink.setAttribute("target", "_self")

    //Create the content for the user
    chatLink.appendChild(content)
    content.setAttribute("class", "content")

    //Create the image for the user
    content.appendChild(usersPic)
    usersPic.setAttribute("src", `${window.location.href.substring(0)}assets/img/${data.users[i].img}`)
    usersPic.setAttribute("alt", data.users[i].fname)

    //Create details for the user
    content.appendChild(usersInfo)
    usersInfo.setAttribute("class", "details")
    usersInfo.appendChild(usersName)
    usersName.innerHTML = data.users[i].fname + " " + data.users[i].lname
    usersInfo.appendChild(newMsg)
    newMsg.innerHTML = "No new message."

    //show user online status
    chatLink.appendChild(userStatus).innerHTML = `<i class='fas fa-circle'>${data.users[i].status}</i>`
    if(data.users[i].status === "Offline"){
      const userStat = "offline"
      userStatus.setAttribute("class", `status-dot ${userStat}`)
    }else{
      userStatus.setAttribute("class", `status-dot`)
    }
  }


}