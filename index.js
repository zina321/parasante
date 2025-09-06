const productContainer = document.getElementById("products");
const searchInput = document.getElementById("search");
const cartCount = document.getElementById("cart-count");
const cartItems = document.getElementById("cart-items");
const sideCart = document.getElementById("side-cart");
const closeCartBtn = document.getElementById("close-cart");

let produits = [];
let panier = [];

// 🔹 Afficher produits
function afficherProduits(liste) {
  productContainer.inconst productContainer = document.getElementById("products");
const searchInput = document.getElementById("search");
const cartCount = document.getElementById("cart-count");
const cartItems = document.getElementById("cart-items");
const sideCart = document.getElementById("side-cart");
const closeCartBtn = document.getElementById("close-cart");

let produits = [];
let panier = [];

// 🔹 Afficher produits
function afficherProduits(liste) {
  productContainer.innerHTML = "";
  for (let i = 0; i < liste.length; i++) {
    let prod = liste[i];
    productContainer.innerHTML += `
      <div class="product-card">
        <img src="images/${prod.image}" alt="${prod.nom}"/>
        <div class="product-info">
          <h3>${prod.nom}</h3>
          <p>${prod.description}</p>
          <div class="price">${parseFloat(prod.prix).toFixed(2)} TND</div>
          <button class="add-btn" onclick="ajouterAuPanier(${i})">Ajouter au panier</button>
        </div>
      </div>
    `;
  }
}

// 🔹 Ajouter au panier
function ajouterAuPanier(index) {
  let produitExistant = panier.find(p => p.nom === produits[index].nom);
  if (produitExistant) {
    produitExistant.quantite++;
  } else {
    panier.push({ ...produits[index], quantite: 1 });
  }
  mettreAJourPanier();
  ouvrirPanier();
}

// 🔹 Supprimer un produit du panier
function supprimerDuPanier(i) {
  panier.splice(i, 1);
  mettreAJourPanier();
  if (panier.length === 0) {
    document.getElementById("paypal-button-container").style.display = "none";
    document.getElementById("messageCommande").textContent = "";
  }
}

// 🔹 Mettre à jour l'affichage panier
function mettreAJourPanier() {
  cartCount.textContent = panier.length;
  cartItems.innerHTML = "";

  panier.forEach((item, i) => {
    cartItems.innerHTML += `
      <div class="cart-item">
        <img src="images/${item.image || 'default.png'}" alt="${item.nom}">
        <div class="cart-item-details">
          <h4>${item.nom}</h4>
          <p>${parseFloat(item.prix).toFixed(2)} TND</p>
          <input type="number" min="1" value="${item.quantite}" onchange="changerQuantite(${i}, this.value)" />
        </div>
        <button class="remove-btn" onclick="supprimerDuPanier(${i})">Supprimer</button>
      </div>
    `;
  });

  if (panier.length > 0) {
    let total = calculerTotalPanier();
    cartItems.innerHTML += `<div class="cart-total">Total: ${total.toFixed(2)} TND</div>`;
  }
}

// 🔹 Changer la quantité
function changerQuantite(index, nouvelleQuantite) {
  panier[index].quantite = parseInt(nouvelleQuantite);
  mettreAJourPanier();
}

// 🔹 Calculer total panier
function calculerTotalPanier() {
  let total = 0;
  panier.forEach(item => total += parseFloat(item.prix) * item.quantite);
  return total;
}

// 🔹 Envoyer commande
function envoyerCommande() {
  const clientNom = document.getElementById("nom").value.trim();
  const clientAdresse = document.getElementById("adresse").value.trim();
  const clientTelephone = document.getElementById("telephone").value.trim();
  const modePaiement = document.getElementById("mode-paiement").value;

  if (!clientNom || !clientAdresse || !clientTelephone || !modePaiement) {
    alert("Veuillez remplir tous les champs et choisir le mode de paiement.");
    return;
  }

  if (panier.length === 0) {
    alert("Votre panier est vide.");
    return;
  }

  const produitsEnvoyes = panier.map(p => ({
    nom: p.nom,
    prix: p.prix,
    quantite: p.quantite
  }));

  if (modePaiement === "carte") {
    document.getElementById("paypal-button-container").style.display = "block";
    alert("✅ Vous pouvez maintenant payer par carte via PayPal.");
    return;
  }

  document.getElementById("paypal-button-container").style.display = "none";

  fetch("save_commande.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      nom: clientNom,
      adresse: clientAdresse,
      telephone: clientTelephone,
      mode: modePaiement,
      produit: produitsEnvoyes
    })
  })
    .then(res => res.text())
    .then(msg => {
      document.getElementById("messageCommande").innerText = "✅ Commande enregistrée !";
      document.getElementById("messageCommande").style.color = "green";

      panier = [];
      mettreAJourPanier();
      document.getElementById("nom").value = "";
      document.getElementById("adresse").value = "";
      document.getElementById("telephone").value = "";
      document.getElementById("mode-paiement").value = "";
    })
    .catch(err => {
      document.getElementById("messageCommande").innerText = "❌ Erreur lors de l'envoi !";
      document.getElementById("messageCommande").style.color = "red";
    });
}

// 🔹 Search dynamique
searchInput.addEventListener("input", (e) => {
  const val = e.target.value.toLowerCase();
  const filtres = produits.filter(
    p => p.nom.toLowerCase().includes(val) || p.description.toLowerCase().includes(val)
  );
  afficherProduits(filtres);
});

// 🔹 Charger produits depuis PHP
window.addEventListener("load", () => {
  fetch("getProduits.php")
    .then(res => res.json())
    .then(data => {
      produits = data;
      afficherProduits(produits);
    });
});

// 🔹 Side cart
function ouvrirPanier() {
  sideCart.classList.remove("hidden");
  sideCart.classList.add("open");
}

function fermerPanier() {
  sideCart.classList.remove("open");
  setTimeout(() => sideCart.classList.add("hidden"), 300);
}

closeCartBtn.addEventListener("click", fermerPanier);
nerHTML = "";
  for (let i = 0; i < liste.length; i++) {
    let prod = liste[i];
    productContainer.innerHTML += `
      <div class="product-card">
        <img src="images/${prod.image}" alt="${prod.nom}"/>
        <div class="product-info">
          <h3>${prod.nom}</h3>
          <p>${prod.description}</p>
          <div class="price">${parseFloat(prod.prix).toFixed(2)} TND</div>
          <button class="add-btn" onclick="ajouterAuPanier(${i})">Ajouter au panier</button>
        </div>
      </div>
    `;
  }
}

// 🔹 Ajouter au panier
function ajouterAuPanier(index) {
  let produitExistant = panier.find(p => p.nom === produits[index].nom);
  if (produitExistant) {
    produitExistant.quantite++;
  } else {
    panier.push({ ...produits[index], quantite: 1 });
  }
  mettreAJourPanier();
  ouvrirPanier();
}

// 🔹 Supprimer un produit du panier
function supprimerDuPanier(i) {
  panier.splice(i, 1);
  mettreAJourPanier();

  if (panier.length === 0) {
    document.getElementById("paypal-button-container").style.display = "none";
    document.getElementById("messageCommande").textContent = "";
  }
}

// 🔹 Mettre à jour l'affichage panier
function mettreAJourPanier() {
  cartCount.textContent = panier.length;
  cartItems.innerHTML = "";

  panier.forEach((item, i) => {
    cartItems.innerHTML += `
      <div class="cart-item">
        <img src="images/${item.image}" alt="${item.nom}">
        <div class="cart-item-details">
          <h4>${item.nom}</h4>
          <p>${parseFloat(item.prix).toFixed(2)} TND</p>
          <input type="number" min="1" value="${item.quantite}" onchange="changerQuantite(${i}, this.value)" />
        </div>
        <button class="remove-btn" onclick="supprimerDuPanier(${i})">Supprimer</button>
      </div>
    `;
  });

  // Ajouter total
  if (panier.length > 0) {
    let total = calculerTotalPanier();
    cartItems.innerHTML += `<div class="cart-total">Total: ${total.toFixed(2)} TND</div>`;
  }
}

// 🔹 Changer la quantité
function changerQuantite(index, nouvelleQuantite) {
  panier[index].quantite = parseInt(nouvelleQuantite);
  mettreAJourPanier();
}

// 🔹 Calculer total panier
function calculerTotalPanier() {
  let total = 0;
  panier.forEach(item => {
    total += parseFloat(item.prix) * item.quantite;
  });
  return total;
}

// 🔹 Envoyer commande
function envoyerCommande() {
  const clientNom = document.getElementById("nom").value.trim();
  const clientAdresse = document.getElementById("adresse").value.trim();
  const clientTelephone = document.getElementById("telephone").value.trim();
  const modePaiement = document.getElementById("mode-paiement").value;

  if (!clientNom || !clientAdresse || !clientTelephone || !modePaiement) {
    alert("Veuillez remplir tous les champs et choisir le mode de paiement.");
    return;
  }

  if (panier.length === 0) {
    alert("Votre panier est vide.");
    return;
  }

  const produitsEnvoyes = panier.map(p => ({
    nom: p.nom,
    prix: p.prix,
    quantite: p.quantite
  }));

  if (modePaiement === "carte") {
    document.getElementById("paypal-button-container").style.display = "block";
    alert("✅ Vous pouvez maintenant payer par carte via PayPal.");
    return;
  } else {
    document.getElementById("paypal-button-container").style.display = "none";

    fetch("save_commande.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        nom: clientNom,
        adresse: clientAdresse,
        telephone: clientTelephone,
        mode: modePaiement,
        produit: produitsEnvoyes
      })
    })
      .then(res => res.text())
      .then(msg => {
        document.getElementById("messageCommande").innerText = "✅ Commande enregistrée !";
        document.getElementById("messageCommande").style.color = "green";

        panier = [];
        mettreAJourPanier();
        document.getElementById("nom").value = "";
        document.getElementById("adresse").value = "";
        document.getElementById("telephone").value = "";
        document.getElementById("mode-paiement").value = "";
      })
      .catch(err => {
        document.getElementById("messageCommande").innerText = "❌ Erreur lors de l'envoi !";
        document.getElementById("messageCommande").style.color = "red";
      });
  }
}

// 🔹 Scroll to cart
function scrollToCart() {
  document.getElementById("cart-section").scrollIntoView({ behavior: "smooth" });
}

// 🔹 Search dynamique
searchInput.addEventListener("input", (e) => {
  const val = e.target.value.toLowerCase();
  const filtres = produits.filter(
    p => p.nom.toLowerCase().includes(val) || p.description.toLowerCase().includes(val)
  );
  afficherProduits(filtres);
});

// 🔹 Charger produits depuis PHP
window.onload = function () {
  fetch("getProduits.php")
    .then(res => res.json())
    .then(data => {
      produits = data;
      afficherProduits(produits);
    });
};

// 🔹 Side cart
function ouvrirPanier() {
  sideCart.classList.add("open");
}

function fermerPanier() {
  sideCart.classList.remove("open");
}


closeCartBtn.addEventListener("click", fermerPanier);
