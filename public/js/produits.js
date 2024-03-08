
const produits_parent = document.getElementById("produits-parent");
console.log(produits_parent);

fetch("https://127.0.0.1:8000/produits-json",{
    method: "GET",
    headers:{
        'Content-type': 'application/json'
    }
})
    .then(response => response.json())
    .then(produits => {
        console.log(produits)
        let carte_produit = produits.map((produit) =>
        `
            <div>
                <p class="text-warning">${produit.name}</p>
                <p class="text-warning">${produit.distributeur}</p>
            </div>
        `
        )
        produits_parent.innerHTML = carte_produit.join(' ');
    })
    .catch(erreur => console.error(erreur))

