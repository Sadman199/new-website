<script>
(function () {
    $('.select2').select2({ width: '100%' });

    let programIndex = document.querySelectorAll('.program-row').length;
    let faqIndex = document.querySelectorAll('.faq-row').length;

    document.getElementById('add-program')?.addEventListener('click', function () {
        const tpl = document.getElementById('program-row-template').innerHTML.replace(/__INDEX__/g, programIndex++);
        document.getElementById('programs-repeater').insertAdjacentHTML('beforeend', tpl);
    });

    document.getElementById('add-faq')?.addEventListener('click', function () {
        const tpl = document.getElementById('faq-row-template').innerHTML.replace(/__INDEX__/g, faqIndex++);
        document.getElementById('faqs-repeater').insertAdjacentHTML('beforeend', tpl);
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-program')) {
            e.target.closest('.program-row')?.remove();
        }
        if (e.target.closest('.remove-faq')) {
            e.target.closest('.faq-row')?.remove();
        }
    });
})();
</script>
