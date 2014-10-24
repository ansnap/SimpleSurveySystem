$(document).ready(function () {
    if ($('.create-poll').length) {
        $('[href="#add-question"]').click(function () {
            var question = $('.question').eq(0).clone().removeClass('invisible').insertBefore('[type="submit"]');
            question.find('[href="#add-answer"]').trigger('click').trigger('click');
            
            renumQuestions();
            return false;
        });

        $('.create-poll').on('click', '[href="#add-answer"]', function () {
            var answer = $('.answer').eq(0).clone().removeClass('invisible');

            var question = $(this).closest('fieldset');
            question.append(answer);

            renumQuestions();
            return false;
        });

        function renumQuestions() {
            $('.question').not('.invisible').each(function (index) {
                $(this).find('.question-title, .question-required, .question-type input, .answer input').each(function () {
                    var name = $(this).attr('name').replace(/\[.*?\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });
            });
        }
    }
});