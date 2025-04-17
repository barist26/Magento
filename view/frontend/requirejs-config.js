var config = {
    paths: {
        'pdfjs': 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min',
        'turnjs': 'https://cdnjs.cloudflare.com/ajax/libs/turn.js/4.1.0/turn.min'
    },
    shim: {
        'turnjs': {
            deps: ['jquery']
        }
    }
};
