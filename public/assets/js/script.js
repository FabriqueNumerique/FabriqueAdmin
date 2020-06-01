
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});


// confirmation message to delete
let supprimer = document.querySelectorAll('.delete-record');
for (let i = 0; i < supprimer.length; i++) {
  supprimer[i].addEventListener('click', function () {
    let modal = this.parentElement.querySelector('.delete-confirm')
    modal.style.display = 'block'
    
  })
}


// delete utilisateur
// let supprimer_user = document.querySelectorAll('.delete_user');

// function delet_user(event) {
//     event.preventDefault();
//     let message = document.querySelector('#message')
//     let url = this.href;
//     fetch(url).then(data => {
//         data.json()
//           .then(data => {
//             message.textContent = data.message
//                 $(this).parent().parent().remove();
//           }) 
//     })
// }


// for (let i = 0; i < supprimer_user.length; i++) {
//   supprimer_user[i].addEventListener('click', delet_user);
// }

// fin delete utilisateur


// delete promotion
// let supprimer_promotion = document.querySelectorAll('.delete_promotion');

// function delet_promotion(event) {
//   event.preventDefault();
//   let url = this.href;
//   fetch(url).then(data => {
//     data.json()
//       .then(data => {
//         document.querySelector('#message').textContent = data.message
//         $(this).parent().parent().remove();
//       })
//   })
// }

// for (let i = 0; i < supprimer_promotion.length; i++) {
//   supprimer_promotion[i].addEventListener('click', delet_user);
// }

// end delete promotion



// edit user

// let modifier = document.querySelectorAll('.modif_user');


// fin edit user


// let chercher_user = document.querySelector('#chercher_user');
// console.log(chercher_user.parentNode.action)

// function find_user(event) {
//   event.preventDefault();
//   let url = this.parentNode.action;
//   fetch(url).then(data => {
//     data.json()
//       .then(data => {
//         console.log(data[users])
//       })
//   })
// }
// chercher_user.addEventListener('click', find_user)













