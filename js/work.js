document.addEventListener('DOMContentLoaded', function () {
        const deliveryTimes = document.querySelectorAll('.delivery-time');
        deliveryTimes.forEach(function (element) {
            const timeInSeconds = element.getAttribute('data-time');
            const days = Math.floor(timeInSeconds / (24 * 3600));
            const hours = Math.floor((timeInSeconds % (24 * 3600)) / 3600);
            const minutes = Math.floor((timeInSeconds % 3600) / 60);
            const seconds = timeInSeconds % 60;

            let timeString = '';

            if (days > 0) {
                timeString += days + ' nap ';
            }
            if (hours > 0) {
                timeString += hours + ' óra ';
            }
            if (minutes > 0) {
                timeString += minutes + ' perc ';
            }
            if (seconds > 0) {
                timeString += seconds + ' másodperc';
            }

            element.textContent = timeString.trim();
        });
    });