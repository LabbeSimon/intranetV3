// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/assets/js/app.js
// @author davidannebicque
// @project intranetV3
// @lastUpdate 20/07/2020 18:05

// any CSS you import will output into a single css file (app.css in this case)
import '@fortawesome/fontawesome-free/css/all.min.css'
import 'bootstrap-select/dist/css/bootstrap-select.min.css'

import '../css/app.scss'

import $ from 'jquery'

require('bootstrap')

let lookup = {}

import PerfectScrollbar from 'perfect-scrollbar'
import './validator-bs4'
import {getDataOptions} from './util'
import './material'
import './search'
import './modaler'

require('bootstrap-select')


// require('./theme')
$(document).ready(function () {
  // script pour afficher le fichier selectionné avec bootstrap4
  $('.custom-file input').change(function (e) {
    const files = []
    for (let i = 0; i < $(this)[0].files.length; i++) {
      files.push($(this)[0].files[i].name)
    }
    $(this).next('.custom-file-label').html(files.join(', '))
  })

  var preloader = $('.preloader')
  if (preloader.length) {
    var speed = preloader.dataAttr('hide-spped', 600)
    preloader.fadeOut(speed)
  }

  $(document).on('focus', '.topbar-search input', function () {
    $(this).closest('.topbar-search').find('.lookup-placeholder span').css('opacity', '0')
  })

  $(document).on('blur', '.topbar-search input', function () {
    $(this).closest('.topbar-search').find('.lookup-placeholder span').css('opacity', '1')
  })

  $(document).on('click', '#lookup', function (e) {
    e.preventDefault()
    var target = $('#lookup-full')

    if (target !== false) {
      lookup.toggle(target)
    }
  })

  $(document).on('click', '#lookup-close', function () {
    lookup.toggle($('#lookup-full'))
  })

  //tooltip
  updateInterface()

  $('[data-provide="validation"]').validator()
})

$(document).ajaxComplete(function () {
  updateInterface()
})

function updateInterface () {
  //selectpicker
  $('.selectpicker').selectpicker({
    iconBase: '',
    tickIcon: 'ti-check',
    style: 'btn-light',
    size: 10,
    liveSearch: true
  })

  // Fullscreen
  //
  $(document).on('click', '.card-btn-fullscreen', function () {
    $(this).closest('.card').toggleClass('card-fullscreen').removeClass('card-maximize')
  })

  // Slide up/down
  $(document).on('click', '.card-btn-slide', function () {
    console.log('toto')
    $(this).toggleClass('rotate-180').closest('.card').find('.card-content').slideToggle()
  })

  //modaler
  $(document).on('click', '[data-provide~="modaler"]', function () {
    modaler(getDataOptions($(this)))
  })

  //tooltip
  $('[data-provide~="tooltip"]').each(function () {
    var color = ''

    if ($(this).hasDataAttr('tooltip-color')) {
      color = ' tooltip-' + $(this).data('tooltip-color')
    }

    $(this).tooltip({
      container: 'body',
      trigger: 'hover',
      template: '<div class="tooltip' + color + '" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
    })
  })
}

// Open fullscreen lookup
//
lookup.toggle = function (e) {
  if ($(e).hasClass('reveal')) {
    lookup.close(e)
  } else {
    lookup.open(e)
  }
}

// Close fullscreen lookup
//
lookup.close = function (e) {
  $(e).removeClass('reveal')
  $('body').removeClass('no-scroll')
}


// Close fullscreen lookup
//
lookup.open = function (e) {
  $(e).addClass('reveal')
  $(e).find('.lookup-form input').focus()
  $('body').addClass('no-scroll')
}
