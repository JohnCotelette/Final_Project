import "../../../css/components/user/candidate_dashboard.scss";

document.getElementById("avatarUser").addEventListener("mouseover",mouseOver);
document.getElementById("avatarUser").addEventListener("mouseout", mouseOut);

let button = document.querySelector("#avatarUser > button");
let avatarForm = document.querySelector(".formAvatar");
let avatarFormFile = document.getElementById("avatar_avataruser_file");
let submit = document.getElementById("avatar_save");

submit.addEventListener("click", function(event){
    event.preventDefault();
    let data = avatarFormFile.value;

    if (data === '' || data == null || data == undefined) {
        // INNERHTML.// Pierre is the best sucker
    }
    else {
        avatarForm.submit();
    }
});

function mouseOver() {
    document.getElementById("avatarUser").style.opacity = 0.4;
    
    button.classList.remove("invisible");
    button.style.opacity = 1;
}

function mouseOut() {
    document.getElementById("avatarUser").style.opacity = 1;
    button.classList.add("invisible");
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the <span> element that closes the modal
var span = document.getElementById("spanModal");

// When the user clicks on the button, open the modal
button.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
