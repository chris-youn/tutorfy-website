document.addEventListener("DOMContentLoaded", function() {
    // Carousel functionality
    let index = 0;
    const items = document.querySelectorAll(".carousel-item");
    const totalItems = items.length;
    const intervalTime = 3000; // Time in milliseconds

    function showItem(idx) {
        items.forEach((item, i) => {
            item.classList.toggle("active", i === idx);
        });
        document.querySelector(".carousel-inner").style.transform = `translateX(-${idx * 100}%)`;
    }

    function nextItem() {
        index = (index + 1) % totalItems;
        showItem(index);
    }

    function prevItem() {
        index = (index - 1 + totalItems) % totalItems;
        showItem(index);
    }

    document.querySelector(".next").addEventListener("click", nextItem);
    document.querySelector(".prev").addEventListener("click", prevItem);

    setInterval(nextItem, intervalTime);

})