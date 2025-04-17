/**
 * Simplified PDF.js library for demo purposes
 * In a real implementation, you would use the full PDF.js library
 */

var pdfjsLib = (function() {
  'use strict';
  
  function getDocument(url) {
    return {
      promise: new Promise(function(resolve, reject) {
        // Simulate loading a PDF
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'arraybuffer';
        
        xhr.onload = function() {
          if (this.status === 200) {
            // Simulate a PDF document with 5 pages
            resolve({
              numPages: 5,
              getPage: function(pageNum) {
                return Promise.resolve({
                  getViewport: function(options) {
                    return {
                      width: 800 * (options.scale || 1),
                      height: 1100 * (options.scale || 1)
                    };
                  },
                  render: function(renderContext) {
                    var ctx = renderContext.canvasContext;
                    var viewport = renderContext.viewport;
                    
                    // Draw a simple page with page number
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, viewport.width, viewport.height);
                    
                    ctx.strokeStyle = 'black';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(10, 10, viewport.width - 20, viewport.height - 20);
                    
                    ctx.font = '30px Arial';
                    ctx.fillStyle = 'black';
                    ctx.textAlign = 'center';
                    ctx.fillText('PDF Page ' + pageNum, viewport.width / 2, viewport.height / 2);
                    
                    ctx.font = '20px Arial';
                    ctx.fillText('This is a simplified PDF.js implementation', viewport.width / 2, viewport.height / 2 + 50);
                    ctx.fillText('In production, use the full PDF.js library', viewport.width / 2, viewport.height / 2 + 80);
                    
                    return Promise.resolve();
                  }
                });
              }
            });
          } else {
            reject(new Error('Failed to load PDF: ' + this.statusText));
          }
        };
        
        xhr.onerror = function() {
          reject(new Error('Network error while loading PDF'));
        };
        
        xhr.send();
      })
    };
  }
  
  return {
    getDocument: getDocument
  };
})();
