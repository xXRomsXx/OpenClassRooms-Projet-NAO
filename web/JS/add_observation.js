// Gestion de la dropzone des images de l'observation
Dropzone.options.mydropzone = {
  addRemoveLinks: true,
  autoProcessQueue: false, // permet de trier toutes les photos d'un coup à la soumission du formulaire
  autoDiscover: false,
  uploadMultiple: true,
  paramName: 'picture', // permet de récupérer les photos via $_FILE['picture'] 
  clickable: true,
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

// Fonctions de validation en temp reel du formulaire
function checkGPS(field) {
  var regex = /^\-?[0-9]{1,2}\.[0-9]{6}$/;
  if (!regex.test(field.value)) {
    $(field).css("border-color", "red");
  } else {
    $(field).css("border-color", "transparent");
  }
}

$(document).ready(function() {

  // Soumission du formulaire si pas de photos
  $("#form_submit").on('click',function() {
    if($("div[class*='dz-preview']").length == 0) {
      $("form").submit();
    }
  });

  //Datepicker en francais
  $.fn.datepicker.dates.fr = {
    days: ["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"],
    daysShort: ["dim.","lun.","mar.","mer.","jeu.","ven.","sam."],
    daysMin: ["di","lu","ma","me","je","ve","sa"],
    months: ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"],
    monthsShort: ["janv.","févr.","mars","avril","mai","juin","juil.","août","sept.","oct.","nov.","déc."],
    today: "Aujourd'hui",
    monthsTitle: "Mois",
    clear: "Effacer",
    weekStart: 1,
    format: "dd/mm/yyyy"
  }

	//Datepicker choix du jour de l'observation
	$(".datepicker").datepicker({
		autoclose: true,
		language: 'fr',
    format: 'dd/mm/yyyy',
		todayHighlight: true,
    endDate: 'today',
  });

}); // End of documentready()