window.onload = () => {
    like();
}
let images = [
    "images/image2.jpg",
    "images/image3.jpg",
    "images/image1.jpg"
];

function like() {
    let likeButton = document.getElementById("button-like");
    likeButton.addEventListener("click", () => {
        let currentImage = images.shift();

        images.push(currentImage);

        let profileImage = document.getElementById("profile-image");
        profileImage.setAttribute("src", currentImage);
    });
}

let profileButton =  document.getElementById("button-profile");
profileButton.addEventListener("click", function (){
    window.location.href = "/profile";
});