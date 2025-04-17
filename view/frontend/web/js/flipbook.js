define([
    'jquery',
    'domReady!'
], function ($) {
    'use strict';
    
    return function (config) {
        var pdfUrl = config.pdfUrl;
        var $flipbook = $('#flipbook');
        var $container = $('#flipbook-container');
        var $loading = $('.pdf-flipbook-loading');
        var pdfDoc = null;
        var pageRendering = false;
        var pageNumPending = null;
        var scale = 1.5;
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var currentPage = 1;
        var totalPages = 0;
        
        // Add navigation controls
        $container.after(
            '<div class="pdf-flipbook-nav">' +
            '<button id="pdf-flipbook-prev" disabled>&laquo; Previous</button>' +
            '<span class="pdf-flipbook-page-num">Page <span id="pdf-flipbook-current">1</span> of <span id="pdf-flipbook-total">0</span></span>' +
            '<button id="pdf-flipbook-next" disabled>Next &raquo;</button>' +
            '</div>'
        );
        
        var $prevBtn = $('#pdf-flipbook-prev');
        var $nextBtn = $('#pdf-flipbook-next');
        var $currentPageEl = $('#pdf-flipbook-current');
        var $totalPagesEl = $('#pdf-flipbook-total');
        
        // Initialize PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        
        // Load the PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            $totalPagesEl.text(totalPages);
            
            // Create pages for Turn.js
            createPages(pdf).then(function() {
                // Initialize Turn.js
                $flipbook.turn({
                    width: $container.width(),
                    height: $container.height() * 0.9,
                    autoCenter: true,
                    display: 'double',
                    acceleration: true,
                    elevation: 50,
                    gradients: true,
                    when: {
                        turning: function(e, page, view) {
                            currentPage = page;
                            $currentPageEl.text(page);
                            updateNavButtons();
                        },
                        turned: function(e, page, view) {
                            // Load the required page
                            renderPage(page);
                            
                            // Load the next page
                            if (page < totalPages) {
                                renderPage(page + 1);
                            }
                        }
                    }
                });
                
                // Hide loading message
                $loading.hide();
                
                // Enable navigation buttons
                updateNavButtons();
                
                // Handle window resize
                $(window).on('resize', function() {
                    resizeFlipbook();
                });
                
                // Render first page
                renderPage(1);
                if (totalPages > 1) {
                    renderPage(2);
                }
            });
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
            $loading.text('Error loading PDF: ' + error.message);
        });
        
        // Create pages for Turn.js
        function createPages(pdf) {
            return new Promise(function(resolve) {
                // Add hard cover
                $flipbook.append('<div class="hard"><div>Front Cover</div></div>');
                
                // Add pages
                for (var i = 1; i <= pdf.numPages; i++) {
                    $flipbook.append('<div class="page"><canvas id="page-' + i + '"></canvas></div>');
                }
                
                // Add back cover
                $flipbook.append('<div class="hard"><div>Back Cover</div></div>');
                
                resolve();
            });
        }
        
        // Render a specific page
        function renderPage(pageNum) {
            if (pageRendering) {
                pageNumPending = pageNum;
                return;
            }
            
            pageRendering = true;
            
            // Get the page
            pdfDoc.getPage(pageNum).then(function(page) {
                var viewport = page.getViewport({ scale: scale });
                var canvas = document.getElementById('page-' + pageNum);
                
                if (!canvas) {
                    pageRendering = false;
                    return;
                }
                
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: canvas.getContext('2d'),
                    viewport: viewport
                };
                
                page.render(renderContext).promise.then(function() {
                    pageRendering = false;
                    
                    if (pageNumPending !== null) {
                        // New page rendering is pending
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
        }
        
        // Update navigation buttons
        function updateNavButtons() {
            $prevBtn.prop('disabled', currentPage === 1);
            $nextBtn.prop('disabled', currentPage >= totalPages);
        }
        
        // Resize flipbook
        function resizeFlipbook() {
            if ($flipbook.turn('is')) {
                $flipbook.turn('size', $container.width(), $container.height() * 0.9);
                $flipbook.turn('resize');
            }
        }
        
        // Navigation button click handlers
        $prevBtn.on('click', function() {
            if (currentPage > 1) {
                $flipbook.turn('previous');
            }
        });
        
        $nextBtn.on('click', function() {
            if (currentPage < totalPages) {
                $flipbook.turn('next');
            }
        });
    };
});
