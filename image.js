// script d'un bouton pour changer l'image
let input = document.getElementById('image');
console.log(input);

if (input.getAttribute('disabled')) {
    let button  = document.createElement('button');
    button.textContent = 'Changer l\'image';
    console.log(input.parentElement);
    input.parentElement.appendChild(button);
    button.addEventListener('click', toggleImageUpdate)
}

function toggleImageUpdate(e) {
    e.preventDefault();
    let button = e.target;
    let input = button.parentElement.querySelector('input');
    if (input.getAttribute('disabled')) {
        input.removeAttribute('disabled');
        button.textContent = 'Ne pas changer l\'image';
    } else {
        input.setAttribute('disabled', true);
        button.textContent = 'changer l\'image';
    }
}