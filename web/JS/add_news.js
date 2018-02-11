// Gestion de la dropzone des images de la news
Dropzone.options.mydropzone = {
  addRemoveLinks: true,
  autoProcessQueue: false, // permet de trier toutes les photos d'un coup à la soumission du formulaire
  autoDiscover: false,
  paramName: 'picture', // permet de récupérer les photos via $_FILE['picture'] 
  clickable: true,
  dictDefaultMessage: 'Cliquez ici ou glisser dans le cadre une photo maximum (fomat .jpg ou .png)',
  dictMaxFilesExceeded: 'Vous ne pouvez pas télécharger plus d\'une photo',
  maxFiles: 1,
  accept: function(file, done) {
    done();
  },
  error: function(file, msg){
      alert(msg);
      this.removeFile(file);
  },
  init: function(file) {
      var myDropzone = this;

      $("#form_submit").on('click',function(e) { // A la soumission du formulaire
        e.preventDefault(); // On ne le poste pas de suite
        myDropzone.processQueue(); // On traite la queue de photos
      });

      // this.on("sendingmultiple", function() {
      // });

      this.on("successmultiple", function(files, response) {   
        $("form").submit(); // Une fois les photos envoyées et traité, on poste le formulaire
      });

      // this.on("errormultiple", function(files, response) {
      // });
  } // End of init()
};

$(document).ready(function() {

  // Soumission du formulaire si pas de photos
  $("#form_submit").on('click',function() {
    if($("div[class*='dz-preview']").length == 0) {
      $("form").submit();
    }
  });

}); // End document.ready()