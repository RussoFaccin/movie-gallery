var frame = wp.media({
    title: 'Select or Upload Media',
    button: {
      text: 'Use this media'
    },
    multiple: false  // Set to true to allow multiple files to be selected
});

var thumbPathField = document.querySelector('.a-thumbPath');

var btnThumb = document.querySelector('.a-selectThumb');
btnThumb.addEventListener('click', (evt) => {
    evt.preventDefault();
    frame.open();
});

frame.on('select', function(evt) {
    var attachment = frame.state().get('selection').first().toJSON().url;
    thumbPathField.value = attachment;
    btnThumb.src = attachment;
})