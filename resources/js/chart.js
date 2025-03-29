const ctx = document.getElementById('temperatureChart').getContext('2d');

const data = {
    labels: ['☀️ 03:00', '☀️ 06:00', '⛅ 09:00', '🌤️ 12:00', '🌞 15:00', '🌥️ 18:00', '🌙 21:00', '🌙 00:00'], // آیکون‌های آب‌وهوا در محور X
    datasets: [{
        label: 'دما (°C)',
        data: [12, 11, 16, 19, 21, 17, 15, 12], // مقدار دما در ساعت‌های مختلف
        borderColor: 'orange', // رنگ خط
        backgroundColor: 'rgba(255, 165, 0, 0.2)', // رنگ پس‌زمینه
        borderWidth: 2,
        fill: true, // پر کردن زیر نمودار
        tension: 0.4 // نرم شدن منحنی
    }]
};

const config = {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: { display: true, text: 'زمان و وضعیت آب‌وهوا' }
            },
            y: {
                display: false // حذف اعداد دما از محور Y
            }
        }
    }
};

new Chart(ctx, config);
