
// on appele la fonction pour la première fois lors du chargement de la page
document.addEventListener('DOMContentLoaded', updateCartItemCount);

// On utilise AJAX pour récupérer le nombre d'articles dans le panier et mettre à jour l'icône du panier
function updateCartItemCount() {
    fetch('/panier/nombre') 
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.getElementById('cartItemCount');
            if (cartCountElement) {
                cartCountElement.innerText = data.count;
            }
        })
        .catch(error => console.error('Erreur lors de la récupération du nombre d\'articles dans le panier', error));
}

// function myFunction() {
//     var x = document.getElementById("myNavList");
//     if (x.className === "navlist") {
//         x.className += "responsive";
//     } else {
//         x.className = "navlist";
//     }
//   }
