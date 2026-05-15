<div id="pdf-container">
  <canvas id="pdf-canvas"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.189/pdf.min.js"></script>
<script>
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.189/pdf.worker.min.js';

  const loadingTask = pdfjsLib.getDocument({
    url: '{{ route("ebook.stream", $ebook->id) }}',
    withCredentials: true,  // kirim cookie session
  });

  loadingTask.promise.then(pdf => {
    pdf.getPage(1).then(page => {
      const canvas = document.getElementById('pdf-canvas');
      const ctx = canvas.getContext('2d');
      const viewport = page.getViewport({ scale: 1.5 });
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      page.render({ canvasContext: ctx, viewport });
    });
  });
</script>