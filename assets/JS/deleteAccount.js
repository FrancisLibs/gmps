// const aClickListener = (e) => {
//   e.preventDefault();
//   path = e.target.parentNode.getAttribute("href");
//   console.log(path);
//   resultat = confirm(
//     "voulez-vous vraiment supprimer ce compte ? Toutes les commandes de ce compte seront supprimées..."
//   );
//   if (resultat) {
//   }
// };

// const accountDeleteButtons = document.querySelectorAll(".accountDeleteButton");

// accountDeleteButtons.forEach((button) => {
//   button.addEventListener("click", aClickListener);
// });

// // script d'effacement d'images dans admin/trick/edit.html.twig
// $(function () {
//   $("#delegation").on("click", "[delete-picture]", function (e) {
//     e.preventDefault();
//     if (confirm("Etes-vous certain de vouloir supprimer cette image ?")) {
//       var path = $(this).attr("href");
//       var image = $(this).parent().parent();
//       var token = $(this).attr("data-token");
//       var jsonToken = { _token: token };
//       jsonToken = JSON.stringify(jsonToken);

//       $.ajax({
//         type: "DELETE",
//         url: path,
//         data: jsonToken,
//         cache: false,
//         contentType: false,
//         processData: false,
//         async: false,
//         dataType: "json",
//         error: function (erreur) {},
//         success: function (data) {
//           image.remove();
//         },
//         complete: function () {},
//       });
//     }
//   });
// });
