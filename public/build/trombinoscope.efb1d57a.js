(self.webpackChunkintranetv3=self.webpackChunkintranetv3||[]).push([[7045],{5979:(t,e,n)=>{"use strict";n.d(e,{v_:()=>a,zD:()=>o});n(3710),n(1539),n(9714),n(6992),n(8783),n(3948),n(285),n(8674);var r=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"GET",r={method:n};return"GET"===n?t+="?"+new URLSearchParams(e).toString():(r.body=JSON.stringify(e),r.headers={"Content-Type":"application/json;charset=utf-8"}),fetch(t,r).then((function(t){return t.json()}))},a=function(t,e){return r(t,e,"POST")},o=function(t,e,n){n.innerHTML="",fetch(Routing.generate(t,e)).then((function(t){return t.text()})).then((function(t){n.innerHTML=t}))}},6763:(t,e,n)=>{"use strict";n(9554),n(4747);var r=n(5979),a=n(2396),o=n(9755);document.querySelectorAll(".semestretrombi").forEach((function(t){t.addEventListener("click",(function(t){var e=t.target,n=document.querySelector("#trombi");t.preventDefault(),document.querySelectorAll(".semestretrombi").forEach((function(t){t.classList.remove("active","show")})),document.querySelectorAll(".enseignanttrombi").forEach((function(t){t.classList.remove("active","show")}));var o=(0,a.bV)(e,"a");(0,r.zD)("trombinoscope_etudiant_semestre",{semestre:o.dataset.sem},n)}))})),o(document).on("click",".changeTypeGroupe",(function(t){t.preventDefault(),o("#trombi").slideUp().empty().load(Routing.generate("trombinoscope_etudiant_semestre_type_groupe",{semestre:o(this).data("semestre"),typegroupe:o(this).data("typegroupe")})).slideDown()})),document.querySelectorAll(".enseignanttrombi").forEach((function(t){t.addEventListener("click",(function(t){var e=t.target,n=document.querySelector("#trombi");t.preventDefault(),document.querySelectorAll(".semestretrombi").forEach((function(t){t.classList.remove("active","show")})),document.querySelectorAll(".enseignanttrombi").forEach((function(t){t.classList.remove("active","show")}));var o=(0,a.bV)(e,"a");(0,r.zD)("trombinoscope_personnel",{type:o.dataset.type},n)}))})),o(document).on("click",".removeEtudiantDepartement",(function(t){o.ajax({url:Routing.generate("user_change_departement",{etudiant:o(this).data("etudiant"),departement:null}),method:"POST",success:function(t){(0,a.qX)("Mofification enregistrée !","success")},error:function(t){(0,a.qX)("Erreur lors de la sauvegarde de la modification !","danger")}})}))},2396:(t,e,n)=>{"use strict";n.d(e,{qX:()=>g,FX:()=>D,xQ:()=>E,bV:()=>b,XQ:()=>S,zl:()=>x});n(4916),n(3123),n(3843),n(3710),n(3210),n(5306),n(9653);var r=n(6455),a=n.n(r),o=(n(9070),n(7941),n(2526),n(7327),n(5003),n(9554),n(4747),n(9337),n(3321),n(8588)),i=n.n(o);function s(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function c(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?s(Object(n),!0).forEach((function(e){l(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):s(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function u(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function l(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}const d=new(function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),l(this,"defaultOptions",{close:!0,duration:3e3,className:"toast",escapeMarkup:!1,gravity:"top",position:"right",stopOnFocus:!0})}var e,n,r;return e=t,(n=[{key:"show",value:function(t,e){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:{};(r=c(c({},this.defaultOptions),r)).className+=" toast-"+t;var a='<div class="toast-wrapper">';n&&(a+='<div class="toast-head">'+n+"</div>"),a+='<div class="toast-body">'+e+"</div>",a+="</div>",r.text=a,i()(r).showToast()}},{key:"error",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("error",t,e,n)}},{key:"warning",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("warning",t,e,n)}},{key:"success",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("success",t,e,n)}},{key:"info",value:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};this.show("info",t,e,n)}}])&&u(e.prototype,n),r&&u(e,r),t}());var f,p,m,v=n(9755),h=n(9755),y=!1;function b(t,e){for(e=e.toLowerCase();t&&t.nodeName.toLowerCase()!=e;)t=t.parentNode;return t||null}function g(t,e){switch(e){case"success":d.success(t);break;case"danger":d.error(t);break;case"warning":d.warning(t);break;case"info":d.info(t)}}f=v(location).attr("pathname"),p=f.split("/"),m=2,"index.php"===p[1]&&p.length>1&&(m=3),"super-administration"===p[m]&&(m+=1),""===p[p.length-1]&&p.pop(),v(".menu-item").removeClass("active"),v("#menu-"+p[m]).addClass("active"),a().mixin({customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),v(document).on("click",".supprimer",(function(t){t.preventDefault();var e=v(this).attr("href"),n=v(this).data("csrf");a().fire({title:"Confirmer la suppression ?",text:"L'opération ne pourra pas être annulée.",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Oui, je confirme",cancelButtonText:"Non, Annuler",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}).then((function(r){r.value?v.ajax({url:e,type:"DELETE",data:{_token:n},success:function(e){if(e.hasOwnProperty("redirect")&&e.hasOwnProperty("url"))document.location.href=e.url;else{var n=b(t.target,"tr");n.parentNode.removeChild(n),g("Suppression effectuée avec succès","success"),a().fire({title:"Supprimé!",text:"L'enregistrement a été supprimé.",icon:"success",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}},error:function(t,e,n){a().fire({title:"Erreur lors de la suppression!",text:"Merci de renouveler l'opération",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1}),g("Erreur lors de la tentative de suppression","danger")}}):"cancel"===r.dismiss&&a().fire({title:"Cancelled",text:"OK! Tout est comme avant !",icon:"error",confirmButtonText:"OK",customClass:{confirmButton:"btn btn-primary",cancelButton:"btn btn-secondary"},buttonsStyling:!1})}))}));var w="",k="text",O=!1;function C(t){var e=v("#myedit-input-"+t).val();v.ajax({url:w.attr("href"),data:{field:w.data("field"),value:e,type:k},method:"POST",success:function(){w.html(e),v("#myEdit-zone-"+t).replaceWith(w),O=!1}})}function E(t,e){var n={};return v.each(v(t).data(),(function(t,r){if("provide"!=(t=t.replace(/-([a-z])/g,(function(t){return t[1].toUpperCase()})))){if(null!=e)switch(e[t]){case"bool":r=Boolean(r);break;case"num":r=Number(r);break;case"array":r=r.split(",")}n[t]=r}})),n}function S(t){t.removeClass("is-valid").addClass("is-invalid")}function x(t){t.removeClass("is-invalid").addClass("is-valid")}function D(t){t.removeClass("is-invalid").removeClass("is-valid")}v(document).on("click",".myedit",(function(t){t.preventDefault(),w=v(this);var e,n,r="";O=!0,void 0!==v(this).data("type")&&(k=v(this).data("type")),"select"===v(this).data("type")||("textarea"===v(this).data("type")?(e=v(this),n=Date.now(),y=!0,r='<div id="myEdit-zone-'+n+'">\n                      <textarea rows="5" class="form-control" id="myedit-input-'+n+'">'+e.html().trim()+'</textarea>\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide" data-key="'+n+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+n+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'):r=function(t){var e=Date.now();return'<div id="myEdit-zone-'+e+'" class="input-group">\n                      <input type="text" class="form-control" id="myedit-input-'+e+'" value="'+t.html().trim()+'" >\n                      <span class="input-group-append">\n <button class="btn btn-success-outline myedit-valide"  data-key="'+e+'"><i class="fas fa-check"></i></button>\n                        <button class="btn btn-danger-outline myedit-annule"  data-key="'+e+'"><i class="fas fa-times"></i></button>\n                      </span>\n                    </div>'}(v(this))),v(this).replaceWith(r),v("#myedit-input").focus()})),v(document).on("keyup","#myedit-input",(function(t){13===t.keyCode&&!1===y?C():27===t.keyCode&&v("#myEdit-zone").replaceWith(w)})),v(document).on("click",".myedit-valide",(function(t){y=!1,t.preventDefault(),C(v(this).data("key"))})),v(document).on("keypress",(function(t){!0===O&&!1===y&&13===t.which&&(t.preventDefault(),C(v(this).data("key"))),!0===O&&!1===y&&27===t.which&&(t.preventDefault(),v("#myEdit-zone-"+v(this).data("key")).replaceWith(w))})),v(document).on("click",".myedit-annule",(function(t){t.preventDefault(),v("#myEdit-zone-"+v(this).data("key")).replaceWith(w)})),h.fn.dataAttr=function(t,e){return v(this)[0].getAttribute("data-"+t)||e},h.fn.hasDataAttr=function(t){return v(this)[0].hasAttribute("data-"+t)}}},t=>{"use strict";t.O(0,[9755,2109,4093,1870,8771,5322],(()=>{return e=6763,t(t.s=e);var e}));t.O()}]);