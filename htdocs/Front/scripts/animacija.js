
function openComment(){
    document.getElementsByClassName("commentBox")[0].style.height="40%";
    document.getElementsByClassName("commentBox")[0].style.opacity="100%";
}

function closeComment(){
    console.log("Zatvara");
    document.getElementsByClassName("commentBox")[0].style.height="0px";
    document.getElementsByClassName("commentBox")[0].style.opacity="0%";
}
