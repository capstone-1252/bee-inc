function equalizeCardTitles() {
    const productSections = document.querySelectorAll('.product-section');
    const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);

    productSections.forEach(productSection => {
        const productTitles = productSection.querySelectorAll('.product-card h2, .product-card h3');

        productTitles.forEach(title => title.style.minHeight = '');

        const heights = [...productTitles].map(title => title.offsetHeight);

        const maxHeight = Math.max(...heights);

        const maxHeightRem = maxHeight / rootFontSize;

        const THRESHOLD_REM = 3;

        if (maxHeightRem > THRESHOLD_REM) {
            productTitles.forEach(individiaulTitle => individiaulTitle.style.minHeight = maxHeightRem + 'rem');
        }
    });
}

document.addEventListener('DOMContentLoaded', equalizeCardTitles);
window.addEventListener('resize', equalizeCardTitles);
