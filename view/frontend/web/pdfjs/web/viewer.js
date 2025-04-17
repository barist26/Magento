/* Simple PDF.js viewer implementation */
document.addEventListener('DOMContentLoaded', function() {
  'use strict';

  // Get the URL parameters
  var url = new URL(window.location.href);
  var pdfUrl = url.searchParams.get('file');
  
  if (!pdfUrl) {
    document.querySelector('.loadingIcon').textContent = 'Error: No PDF file specified';
    return;
  }

  var viewerContainer = document.getElementById('viewerContainer');
  var viewer = document.getElementById('viewer');
  var loadingIcon = document.querySelector('.loadingIcon');

  // Load the PDF
  pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc) {
    loadingIcon.style.display = 'none';
    
    // Load each page
    for (var pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
      renderPage(pdfDoc, pageNum);
    }
  }).catch(function(error) {
    loadingIcon.textContent = 'Error: ' + error.message;
  });

  function renderPage(pdfDoc, pageNumber) {
    pdfDoc.getPage(pageNumber).then(function(page) {
      var viewport = page.getViewport({ scale: 1.5 });
      
      // Create a div for this page
      var pageDiv = document.createElement('div');
      pageDiv.className = 'page';
      pageDiv.style.width = viewport.width + 'px';
      pageDiv.style.height = viewport.height + 'px';
      viewer.appendChild(pageDiv);
      
      // Create a canvas for this page
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      canvas.width = viewport.width;
      canvas.height = viewport.height;
      pageDiv.appendChild(canvas);
      
      // Render the page
      var renderContext = {
        canvasContext: context,
        viewport: viewport
      };
      
      page.render(renderContext);
    });
  }
});
