(window.webpackJsonp=window.webpackJsonp||[]).push([["informations"],{PxiF:function(t,n,e){(function(t){e("fbCW"),t(document).on("click",".changeinformation",(function(n){n.preventDefault(),n.stopPropagation(),t("#header-title").empty().append(t(this).text()),t(".changeinformation").removeClass("active show"),t(this).addClass("active show"),t("#mainContent").empty().load(t(this).attr("href"))})),t(document).on("click",".addLike",(function(){var n=t(this).parent().find("span");t.ajax({url:Routing.generate("information_like",{slug:t(this).data("article")}),method:"post",success:function(t){n.text(t)},error:function(){addCallout("Erreur lors de la gestion de vos articles favoris","danger")}})}))}).call(this,e("EVdn"))}},[["PxiF","runtime",0,4]]]);