/* =============================================================================
 * browse.js — client-side search filter for the activity grid
 * Port of Browse.dc.html's search behaviour to vanilla JS.
 *
 * Filters the server-rendered .activity-card elements by title / description /
 * tags (case-insensitive), via each card's data-search attribute. Shows the
 * "no results" message when nothing matches.
 * ========================================================================== */
(function () {
  'use strict';

  function init() {
    var input = document.getElementById('activitySearch');
    var grid  = document.getElementById('activityGrid');
    var empty = document.getElementById('noResults');
    if (!input || !grid) { return; }

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.activity-card'));

    function apply() {
      var q = input.value.trim().toLowerCase();
      var visible = 0;

      cards.forEach(function (card) {
        var hay = card.getAttribute('data-search') || '';
        var match = q === '' || hay.indexOf(q) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) { visible++; }
      });

      if (empty) {
        empty.style.display = visible === 0 ? 'block' : 'none';
      }
    }

    input.addEventListener('input', apply);
    apply();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
