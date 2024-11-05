document.addEventListener("DOMContentLoaded", function () {
    const images = [
        "/images/image1.jpg",
        "/images/image2.jpg",
        "/images/image3.jpg",
        "/images/image4.jpg"
    ];

    const profileImage = document.querySelector(".profile-image");
    const buttonLike = document.getElementById("button-like");
    const buttonUnlike = document.getElementById("button-unlike");

    // Función para cambiar a una imagen aleatoria
    function changeRandomImage() {
        const randomIndex = Math.floor(Math.random() * images.length);
        profileImage.src = images[randomIndex];
    }

    // Agregar eventos de clic a los botones
    buttonLike.addEventListener("click", changeRandomImage);
    buttonUnlike.addEventListener("click", changeRandomImage);
});
