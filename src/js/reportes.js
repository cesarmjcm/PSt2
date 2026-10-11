async function abrirReportePdf(url, titulo, boton, mostrarAlerta) {
    const textoOriginal = boton.innerHTML;
    let ventanaReporte = null;
    let urlReporte = null;
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';

    try {
        ventanaReporte = window.open('', '_blank');
        if (!ventanaReporte) {
            throw new Error('Permite las ventanas emergentes para abrir el reporte PDF.');
        }

        ventanaReporte.document.title = 'Generando ' + titulo.toLowerCase();
        ventanaReporte.document.body.textContent = 'Generando reporte PDF...';

        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            cache: 'no-store'
        });
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            throw new Error('La sesión pudo expirar. Recarga la página e inténtalo de nuevo.');
        }

        const resultado = await response.json();
        if (!response.ok || !resultado.success) {
            throw new Error(resultado.message || 'No se pudo generar el PDF.');
        }

        const contenido = atob(resultado.pdf);
        const bytes = new Uint8Array(contenido.length);
        for (let i = 0; i < contenido.length; i++) {
            bytes[i] = contenido.charCodeAt(i);
        }

        const archivo = new Blob([bytes], { type: 'application/pdf' });
        urlReporte = URL.createObjectURL(archivo);
        ventanaReporte.location.replace(urlReporte);

        const urlLiberar = urlReporte;
        const ventanaLiberar = ventanaReporte;
        const liberarUrl = window.setInterval(() => {
            if (ventanaLiberar.closed) {
                window.clearInterval(liberarUrl);
                URL.revokeObjectURL(urlLiberar);
            }
        }, 1000);

        if (mostrarAlerta) {
            mostrarAlerta('El reporte PDF se abrió en una nueva pestaña.', 'success');
        }
    } catch (error) {
        if (ventanaReporte && !ventanaReporte.closed) {
            ventanaReporte.close();
        }
        if (urlReporte) {
            URL.revokeObjectURL(urlReporte);
        }
        if (mostrarAlerta) {
            mostrarAlerta(error.message || 'No se pudo generar el PDF.', 'error');
        }
    } finally {
        boton.disabled = false;
        boton.innerHTML = textoOriginal;
    }
}
