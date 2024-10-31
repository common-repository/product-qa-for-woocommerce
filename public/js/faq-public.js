/**
 * Public-facing js.
 */
jQuery(function ($) {
    'use strict';

    /**
     * Markup @'public/templates/list-questions-answers.php
     */
    $('.answer-button').click(function () {
        var id = $(this).attr('data-id');
        $('#question-' + id).slideToggle('slow');
    });

});