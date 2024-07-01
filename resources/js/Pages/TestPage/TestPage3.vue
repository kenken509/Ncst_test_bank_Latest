<template>
    <div id="pdf-convert" class="pdf-convert">
        <p>Well, let me tell you something, ...</p>
        <p class="break-before-always">Sure, go ahead, laugh...</p>
        <p>Maybe we can live without...</p>
        <p>Look. If you think this is...</p>
    </div>
    <button @click="saveQuestionnaireToPDF()" type="button" class="btn-primary w-full p-2">Save</button>
</template>

<style>
.break-before-always {
    page-break-before: always;
}
</style>

<script setup>
import jsPDF from 'jspdf';
import html2pdf from 'html2pdf.js'; // Ensure the correct import path

function saveQuestionnaireToPDF() {
    const element = document.getElementById('pdf-convert');
    const opt = {
        margin: [0.4, 0.4, 0.5, 0.4], // [top, left, bottom, right]
        filename: 'questionnaire.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    // Function to add footers to each page
    function addFooters(pdf) {
        const pageCount = pdf.internal.getNumberOfPages();
        const currentDate = new Date().toLocaleDateString();
        pdf.setFontSize(8); // Set font size to 8px

        for (let i = 1; i <= pageCount; i++) {
            pdf.setPage(i);
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            // Draw a thinner line at the bottom of the page
            pdf.setLineWidth(0.01); // Set line width to the smallest visible value
            pdf.line(0.5, pageHeight - 0.6, pageWidth - 0.5, pageHeight - 0.6);

            // Adding current date on the left
            pdf.text(currentDate, 0.5, pageHeight - 0.4);

            // Calculate the width of the text
            const pageText = `Page ${i} of ${pageCount}`;
            const textWidth = pdf.getTextDimensions(pageText).w;

            // Adding page number on the right
            pdf.text(pageText, pageWidth - textWidth - 0.5, pageHeight - 0.4);
        }
    }

    // Generate the PDF
    html2pdf().from(element).set(opt).toPdf().get('pdf').then((pdf) => {
        addFooters(pdf);
    }).save();
}
</script>
