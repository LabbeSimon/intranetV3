(self.webpackChunk=self.webpackChunk||[]).push([[4152],{26381:(e,t,n)=>{"use strict";n.d(t,{r:()=>a});var a={decimal:"",emptyTable:"Aucune donn&eacute;e disponible dans le tableau",info:"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",infoEmpty:"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",infoFiltered:"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",infoPostFix:"",thousands:",",lengthMenu:"Afficher _MENU_ &eacute;l&eacute;ments",loadingRecords:"Chargement en cours...",processing:"Traitement en cours...",search:"Rechercher&nbsp;:",zeroRecords:"Aucun &eacute;l&eacute;ment &agrave; afficher",paginate:{first:"Premier",last:"Dernier",next:"Suivant",previous:"Pr&eacute;c&eacute;dent"},aria:{sortAscending:": activer pour trier la colonne par ordre croissant",sortDescending:": activer pour trier la colonne par ordre d&eacute;croissant"}}},89097:(e,t,n)=>{"use strict";n(74916),n(23123),n(73210);var a=n(1128),s=(n(54671),n(19755)),i=n.n(s),r=n(26381);function o(e,t){i().ajax({type:"GET",url:Routing.generate("application_personnel_absence_get_ajax",{matiere:i()("#absence-matiere").val()}),dataType:"json",success:function(n){var a=e.split("/"),s=a[2].trim()+"-"+a[1].trim()+"-"+a[0].trim();for(var r in 4===t.length&&(t="0"+t),n)if(r==s&&void 0!==n[r][t])for(var o=0;o<n[r][t].length;o++)i()("#etu_"+n[r][t][o]).addClass("absent")}})}i()(document).on("click",".absChangeTypeGroupe",(function(e){e.preventDefault(),e.stopPropagation(),i()(".absChangeTypeGroupe").removeClass("btn-primary"),i()(this).addClass("btn-primary"),i()("#listeEtudiantsAbsences").load(Routing.generate("api_absence_liste_etudiant",{typegroupe:i()(this).data("typegroupe")}),(function(){console.log("then...");var e=i()("#absence-date"),t=i()("#absence-heure");o(e.val(),t.val())}))})),i()(document).on("change","#absence-matiere",(function(){var e=i()(".etudiant"),t=i()("#absence-date"),n=i()("#absence-heure");e.removeClass("absent"),o(t.val(),n.val())})),i()(document).on("change","#absence-date",(function(){var e=i()(".etudiant"),t=i()("#absence-date"),n=i()("#absence-heure");e.removeClass("absent"),o(t.val(),n.val())})),i()(document).on("change","#absence-heure",(function(){var e=i()(".etudiant"),t=i()("#absence-date"),n=i()("#absence-heure");e.removeClass("absent"),o(t.val(),n.val())})),i()(document).on("click",".etudiant",(function(){var e=i()(this).attr("id").split("_");i()(this).hasClass("absent")?(i()(this).removeClass("absent"),i().ajax({type:"POST",url:Routing.generate("application_personnel_absence_saisie_ajax",{matiere:i()("#absence-matiere").val(),etudiant:e[1]}),dataType:"json",data:{date:i()("#absence-date").val(),heure:i()("#absence-heure").val(),action:"suppr"},error:function(){(0,a.qX)("Erreur lors de la tentative de suppression de l'absence !","danger")},success:function(e){e,(0,a.qX)("La suppression a été effectuée avec succés !","success")}})):(i()(this).addClass("absent"),i().ajax({type:"POST",url:Routing.generate("application_personnel_absence_saisie_ajax",{matiere:i()("#absence-matiere").val(),etudiant:e[1]}),dataType:"json",data:{date:i()("#absence-date").val(),heure:i()("#absence-heure").val(),action:"saisie"},error:function(e){"out"===e.responseText?(0,a.qX)("Le délai pour l'enregistrement est dépassé. Contactez le responsable de la departement","danger"):(0,a.qX)("Erreur lors de l'enregistrement.","danger")},success:function(e){(0,a.qX)("Absence enregistrée avec succés !","success")}}))})),i()("#liste-absences").dataTable({language:r.r,fnRowCallback:function(e,t,n,a){"non"===t[5]||"no"===t[5]||"No"===t[5]||"Non"===t[5]?i()("td",e).css("background-color","#fce3e3"):i()("td",e).css("background-color","#e3fcf2")}})},1128:(e,t,n)=>{"use strict";n.d(t,{qX:()=>p,xQ:()=>h,XQ:()=>y,zl:()=>g,FX:()=>C});n(9653),n(74916),n(15306),n(23123),n(73210);var a,s,i,r=n(19755),o=n.n(r),c=n(86455),u=n.n(c),l=n(19755),d=!1;function p(e,t){console.log("callout");var n=new Array;n.success="Succès",n.danger="Erreur",n.warning="Attention";var a='<div class="callout callout-'+t+'" role="alert">\n                    <button type="button" class="close" data-dismiss="callout" aria-label="Close">\n                        <span>&times;</span>\n                    </button>\n                    <h5>'+n[t]+"</h5>\n                    <p>"+e+"</p>\n                </div>";o()("#mainContent").prepend(a).slideDown("slow"),o()(".callout").delay(5e3).slideUp("slow")}a=o()(location).attr("pathname"),s=a.split("/"),i=2,"index.php"===s[1]&&s.length>1&&(i=3),"super-administration"===s[i]&&(i+=1),""===s[s.length-1]&&s.pop(),o()(".menu-item").removeClass("active"),o()("#menu-"+s[i]).addClass("active"),u().mixin({customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),o()(document).on("click",".supprimer",(function(e){e.preventDefault();var t=o()(this).attr("href"),n=o()(this).data("csrf");u().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(e){e.value?o().ajax({url:t,type:"DELETE",data:{_token:n},success:function(e){e.hasOwnProperty("redirect")&&e.hasOwnProperty("url")?document.location.href=e.url:(o()("#ligne_"+e).closest("tr").remove(),p("Suppression effectuée avec succès","success"),u().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}))},error:function(e,t,n){u().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),p("Erreur lors de la tentative de suppression","danger")}}):"cancel"===e.dismiss&&u().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))}));var m="",f="text",b=!1;function v(){var e=o()("#myedit-input").val();o().ajax({url:m.attr("href"),data:{field:m.data("field"),value:e,type:f},method:"POST",success:function(){m.html(e),o()("#myEdit-zone").replaceWith(m),b=!1}})}function h(e,t){var n={};return o().each(o()(e).data(),(function(e,a){if("provide"!=(e=e.replace(/-([a-z])/g,(function(e){return e[1].toUpperCase()})))){if(null!=t)switch(t[e]){case"bool":a=Boolean(a);break;case"num":a=Number(a);break;case"array":a=a.split(",")}n[e]=a}})),n}function y(e){e.removeClass("is-valid").addClass("is-invalid")}function g(e){e.removeClass("is-invalid").addClass("is-valid")}function C(e){e.removeClass("is-invalid").removeClass("is-valid")}o()(document).on("click",".myedit",(function(e){e.preventDefault(),m=o()(this);var t,n="";b=!0,void 0!==o()(this).data("type")&&(f=o()(this).data("type")),"select"===o()(this).data("type")||("textarea"===o()(this).data("type")?(t=o()(this),d=!0,n='<div id="myEdit-zone">\n                      <textarea rows="5" class="form-control" id="myedit-input">'+t.html().trim()+'</textarea>\n                      <span class="input-group-append">\n <button class="btn btn-success-outline" id="myedit-valide"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline" id="myedit-annule"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'):n=function(e){return'<div id="myEdit-zone" class="input-group">\n                      <input type="text" class="form-control" id="myedit-input" value="'+e.html().trim()+'" >\n                      <span class="input-group-append">\n <button class="btn btn-success-outline" id="myedit-valide"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline" id="myedit-annule"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'}(o()(this))),o()(this).replaceWith(n),o()("#myedit-input").focus()})),o()(document).on("keyup","#myedit-input",(function(e){13===e.keyCode&&!1===d?v():27===e.keyCode&&o()("#myEdit-zone").replaceWith(m)})),o()(document).on("click","#myedit-valide",(function(e){d=!1,e.preventDefault(),v()})),o()(document).on("keypress",(function(e){!0===b&&!1===d&&13===e.which&&(e.preventDefault(),v()),!0===b&&!1===d&&27===e.which&&(e.preventDefault(),o()("#myEdit-zone").replaceWith(m))})),o()(document).on("click","#myedit-annule",(function(e){e.preventDefault(),o()("#myEdit-zone").replaceWith(m)})),l.fn.dataAttr=function(e,t){return o()(this)[0].getAttribute("data-"+e)||t},l.fn.hasDataAttr=function(e){return o()(this)[0].hasAttribute("data-"+e)}}},0,[[89097,3666,9755,2109,2402,2326,760,2300,4671]]]);