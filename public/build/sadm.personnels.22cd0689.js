(self.webpackChunk=self.webpackChunk||[]).push([[5770],{26381:(e,t,a)=>{"use strict";a.d(t,{r:()=>n});var n={decimal:"",emptyTable:"Aucune donn&eacute;e disponible dans le tableau",info:"Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",infoEmpty:"Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",infoFiltered:"(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",infoPostFix:"",thousands:",",lengthMenu:"Afficher _MENU_ &eacute;l&eacute;ments",loadingRecords:"Chargement en cours...",processing:"Traitement en cours...",search:"Rechercher&nbsp;:",zeroRecords:"Aucun &eacute;l&eacute;ment &agrave; afficher",paginate:{first:"Premier",last:"Dernier",next:"Suivant",previous:"Pr&eacute;c&eacute;dent"},aria:{sortAscending:": activer pour trier la colonne par ordre croissant",sortDescending:": activer pour trier la colonne par ordre d&eacute;croissant"}}},15230:(e,t,a)=>{"use strict";a(54671);var n=a(26381),r=a(19755),s=a(19755);r(document).on("keyup","#sa_login_urca",(function(){var e=r(this).data("departement"),t=r(this).val();t.length>2&&r.ajax({url:Routing.generate("api_personnel_recherche",{needle:t}),dataType:"json",success:function(t){r("#result").empty(),s.each(t,(function(t,a){var n="<tr><td>"+a.nom+"</td><td>"+a.prenom+"</td><td>"+a.numeroHarpege+"</td><td>"+a.username+"</td><td>"+a.mail_univ+'</td><td><a href="#" class="btn btn-success btn-outline btn-square sa_addpersonnel" data-provide="tooltip" data-placement="bottom" title="Ajouter au departement" data-slug="'+a.slug+'" data-departement="'+e+'"><i class="fas fa-plus"></i></a></td></tr>';r("#result").append(n)}))}})})),r(document).on("click",".sa_addpersonnel",(function(){r.ajax({url:Routing.generate("api_personnel_add_to_departement",{slug:r(this).data("slug"),departement:r(this).data("departement")}),dataType:"json",success:function(e){addCallout("Personnel ajouté au departement !","success")}})})),r("#datatableRh").DataTable({processing:!0,serverSide:!0,language:n.r,ajax:Routing.generate("api_all_personnel"),sAjaxDataProp:"data",pageLength:25,order:[[1,"asc"]],columns:[{data:"numero_harpege"},{data:"nom"},{data:"prenom"},{data:"login"},{data:"departements"},{data:"deleted"},{data:"id",sortable:!1,mRender:function(e,t,a){return'<a href="'+Routing.generate("sa_rh_personnel_show",{id:e})+'" class="btn btn-info btn-outline btn-square" data-provide="tooltip"\n   data-placement="bottom" title="Détails"><i class="fa fa-info"></i></a>\n<a href="'+Routing.generate("sa_rh_personnel_edit",{id:e})+'"\n   class="btn btn-warning btn-outline btn-square"\n                                                     data-provide="tooltip"\n                                                     data-placement="bottom"\n                                                     title="Modifier"><i class="fa fa-edit"></i></a>\n<a href="'+Routing.generate("sa_rh_delete_personnel",{id:e})+'" class="btn btn-danger btn-outline btn-square supprimer" data-id="id"            data-provide="tooltip" data-placement="bottom"\n            title="Supprimer"><i class="fa fa-trash"></i></a>'}}]}),r(document).on("change",".change_droit_pf",(function(){r.ajax({url:Routing.generate("sa_personnel_departement_modifier_droit",{pf:r(this).data("pf")}),method:"POST",data:{droit:r(this).val()},success:function(e){addCallout("Droits modifiés !","success")}})})),r(document).on("click","#searchLdap",(function(e){e.preventDefault(),r.ajax({url:Routing.generate("ldap_search"),method:"POST",data:{numero:r("#personnel_numero_harpege").val()},success:function(e){r("#personnel_mail_univ").val(e.mail),r("#personnel_username").val(e.login)}})}))},76091:(e,t,a)=>{var n=a(47293),r=a(81361);e.exports=function(e){return n((function(){return!!r[e]()||"​᠎"!="​᠎"[e]()||r[e].name!==e}))}},73210:(e,t,a)=>{"use strict";var n=a(82109),r=a(53111).trim;n({target:"String",proto:!0,forced:a(76091)("trim")},{trim:function(){return r(this)}})}},0,[[15230,3666,9755,2109,2402,760,2300,4671]]]);