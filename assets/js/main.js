function equalizeCardTitles() {
  const productSections = document.querySelectorAll(".product-section");
  const rootFontSize = parseFloat(
    getComputedStyle(document.documentElement).fontSize
  );

  productSections.forEach((productSection) => {
    const cards = productSection.querySelectorAll(".product-card");
    const usedRows = [];
    const rows = [];

    cards.forEach((card) => {
      const rowKey = Math.round(card.getBoundingClientRect().top);
      const existingRowIndex = usedRows.findIndex(
        (existingKey) => Math.abs(existingKey - rowKey) < 10
      );

      if (existingRowIndex === -1) {
        usedRows.push(rowKey);
        rows.push([card]);
      } else {
        rows[existingRowIndex].push(card);
      }
    });

    rows.forEach((rowCards) => {
      const titles = [];
      const prices = [];

      rowCards.forEach((individualCard) => {
        const title = individualCard.querySelector("h2, h3");
        const price = individualCard.querySelector(".product-price");

        if (title) {
          titles.push(title);
        }

        if (price) {
          prices.push(price);
        }
      });

      // -----------------------------------------
      titles.forEach(
        (individualTitle) => (individualTitle.style.minHeight = "")
      );

      const maxTitleRem =
        Math.max(
          ...titles.map((individualTitle) => individualTitle.offsetHeight)
        ) / rootFontSize;

      if (maxTitleRem > 1.6875) {
        titles.forEach(
          (individualTitle) =>
            (individualTitle.style.minHeight = maxTitleRem + "rem")
        );
      }

      // -----------------------------------------
      prices.forEach(
        (individualPrice) => (individualPrice.style.minHeight = "")
      );

      const maxPriceRem =
        Math.max(
          ...prices.map((individualPrice) => individualPrice.offsetHeight)
        ) / rootFontSize;

      if (maxPriceRem > 2.5) {
        prices.forEach(
          (individualPrice) =>
            (individualPrice.style.minHeight = maxPriceRem + "rem")
        );
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", equalizeCardTitles);
window.addEventListener("resize", equalizeCardTitles);
