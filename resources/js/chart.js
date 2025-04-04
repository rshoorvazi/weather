$(document).ready(function () {

    // تعریف چارت
    const ctx = document.getElementById('temperatureChart').getContext('2d');
    const temperatureChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: window.forecastHours,  // ساعت‌ها به طور داینامیک اضافه می‌شوند
            datasets: [{
                label: 'دما (°C)',
                data: window.forecastTemperatures,  // دماها به طور داینامیک اضافه می‌شوند
                borderColor: 'orange',
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {display: true, text: 'زمان و وضعیت آب‌وهوا'}
                },
                y: {
                    display: false
                }
            }
        }
    });

    // راه‌اندازی Select2
    $('#js-example-basic-single').select2({
        width: '16rem',
        ajax: {
            url: '/api/cities',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // مقدار جستجو شده
                    page: params.page || 1 // شماره صفحه
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.results,
                    pagination: {
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1,
        language: {
            noResults: function () {
                return "هیچ نتیجه‌ای پیدا نشد";
            },
            inputTooShort: function (args) {
                return `لطفاً حداقل ${args.minimum} حرف وارد کنید`;
            }
        }
    });

    // زمانی که شهری انتخاب می‌شود
    $('#js-example-basic-single').on('select2:select', function (e) {
        let cityId = e.params.data.id;

        axios.get(`/api/weather/${cityId}`)
            .then(response => {
                let data = response.data;
                $('#humidity').fadeOut(200, function () {
                    $(this).html(data.current.humidity).fadeIn(200);
                });

                $('#precipitation').fadeOut(200, function () {
                    $(this).html(data.current.precipitation).fadeIn(200);
                });

                $('#wind-speed').fadeOut(200, function () {
                    $(this).html(data.current.wind_speed).fadeIn(200);
                });

                $('#city-name').fadeOut(200, function () {
                    $(this).html(data.city_name).fadeIn(200);
                });
                const weatherClass = data.current.weather_class[0]; // مثل card-sunny
                const iconClasses = data.current.weather_class[1];  // مثل ['sunny', 'sun-clouds']

                let $weatherCard = $('#weather-card');

                // مرحله اول: اجرای انیمیشن خروج
                $weatherCard.removeClass('fade-in').addClass('fade-out');
                setTimeout(() => {
                    // تغییر کلاس‌های کارت
                    $weatherCard
                        .removeClass() // حذف همه کلاس‌ها
                        .addClass('card') // کلاس پایه
                        .addClass(weatherClass); // کلاس متناسب با وضعیت هوا

                    // حذف آیکن‌های قبلی
                    $weatherCard.empty();

                    // افزودن آیکن‌های جدید
                    iconClasses.forEach(icon => {
                        $weatherCard.append(`<div class="${icon}"></div>`);
                    });

                    // اجرای انیمیشن ورود
                    $weatherCard.removeClass('fade-out').addClass('fade-in');
                }, 400); // باید با زمان transition در CSS هماهنگ باشد

                // چارت مثل قبل
                const hours = data.forecast.hours;
                const temperatures = data.forecast.temperatures;
                temperatureChart.data.labels = hours;
                temperatureChart.data.datasets[0].data = temperatures;
                temperatureChart.update();
            })
            .catch(error => {
                console.error("خطا در دریافت اطلاعات آب و هوا", error);
            });
    });

});
