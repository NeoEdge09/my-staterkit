<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                <span class="header-toggle me-3">
                    <i class="ph ph-circles-four"></i>
                </span>
            </div>

            <div class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">

                <ul class="d-flex align-items-center">

                    <li class="header-cloud">
                        <a href="#" class="head-icon" role="button" data-bs-toggle="offcanvas"
                            data-bs-target="#cloudoffcanvasTops" aria-controls="cloudoffcanvasTops">
                            <i id="current-weather-icon" class="text-primary f-s-26 me-1"></i>
                            <span id="current-temp">Loading...</span>
                        </a>
                        <div class="offcanvas offcanvas-end header-cloud-canvas " tabindex="-1" id="cloudoffcanvasTops"
                            aria-labelledby="cloudoffcanvasTops">
                            <div class="offcanvas-body p-0 ">
                                <div class="cloud-body">
                                    <div class="cloud-content-box" id="weather-forecast">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>




                    <li class="header-dark">
                        <div class="sun-logo head-icon">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>



                    <li class="header-profile">
                        <a href="#" class="d-block head-icon" role="button" data-bs-toggle="offcanvas"
                            data-bs-target="#profilecanvasRight" aria-controls="profilecanvasRight">
                            <img src="{{ asset('../assets/images/avtar/woman.jpg') }}" alt="avtar"
                                class="b-r-10 h-35 w-35">
                        </a>

                        <div class="offcanvas offcanvas-end header-profile-canvas" tabindex="-1"
                            id="profilecanvasRight" aria-labelledby="profilecanvasRight">
                            <div class="offcanvas-body app-scroll">
                                <ul class="">
                                    <li>
                                        <div class="d-flex-center">
                                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative">
                                                <img src="{{ asset('../assets/images/avtar/woman.jpg') }}"
                                                    alt="" class="img-fluid b-r-10">
                                            </span>
                                        </div>
                                        <div class="text-center mt-2">
                                            <h6 class="mb-0"> Laura Monaldo</h6>
                                            <p class="f-s-12 mb-0 text-secondary">lauradesign@gmail.com</p>
                                        </div>
                                    </li>

                                    <li class="app-divider-v dotted py-1"></li>
                                    <li>
                                        <a class="f-w-500" href="#" target="_blank">
                                            <i class="ph-duotone  ph-user-circle pe-1 f-s-20"></i> Profile Details
                                        </a>
                                    </li>

                                    <li class="app-divider-v dotted py-1"></li>

                                    <li>
                                        <a class="mb-0 text-danger" href="#">
                                            <i class="ph-duotone  ph-sign-out pe-1 f-s-20"></i> Log Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<script>
    const currentTemp = document.getElementById('current-temp');
    const currentWeatherIcon = document.getElementById('current-weather-icon');
    const weatherForecast = document.getElementById('weather-forecast');
    const forecastHtml = document.querySelector('.cloud-content-box');

    const getNextDays = (startDate, days) => {
        const date = new Date(startDate);
        date.setDate(date.getDate() + days);
        return date;
    };

    const weatherImages = {
        'partly cloudy': 'https://api-apps.bmkg.go.id/storage/icon/cuaca/berawan-am.svg',
        'cloudy': 'https://api-apps.bmkg.go.id/storage/icon/cuaca/berawan.svg',
        'clear': 'https://api-apps.bmkg.go.id/storage/icon/cuaca/cerah.svg',
        'rain': 'https://api-apps.bmkg.go.id/storage/icon/cuaca/hujan.svg',
        'thunderstorm': 'https://api-apps.bmkg.go.id/storage/icon/cuaca/hujan-petir.svg'
    };

    const estimateWeather = (lastKnownTemp) => {
        const variation = Math.floor(Math.random() * 3) - 1;
        const weather_desc = 'partly cloudy';

        return {
            t: parseInt(lastKnownTemp) + variation,
            weather_desc: weather_desc,
            hu: Math.floor(Math.random() * (80 - 60) + 60),
            image: weatherImages[weather_desc]
        };
    };
    fetch('/api/weather')
        .then(response => response.json())
        .then(response => {
            const data = response.data[0];
            const realDays = data.cuaca;
            const today = realDays[0][0];

            currentWeatherIcon.innerHTML =
                `<img src="${today.image}" class="weather-svg" alt="weather" width="25">`;
            currentTemp.innerHTML = `${today.t}<sup class="f-s-10">°C</sup>`;

            const lastKnownTemp = realDays[realDays.length - 1][0].t;
            const estimatedDays = Array.from({
                length: 4
            }, (_, i) => [{
                ...estimateWeather(lastKnownTemp),
                local_datetime: getNextDays(realDays[realDays.length - 1][0].local_datetime, i + 1)
            }]);

            const allDays = [...realDays, ...estimatedDays];

            const bgClasses = [
                'bg-primary-900', 'bg-primary-800', 'bg-primary-700',
                'bg-primary-600', 'bg-primary-500', 'bg-primary-400',
                'bg-primary-300'
            ];

            const weatherHtml = allDays.map((day, index) => {
                const date = new Date(day[0].local_datetime);
                const dayName = date.toLocaleDateString('en-US', {
                    weekday: 'short'
                });

                return `
                <div class="cloud-box ${bgClasses[index]}">
                    <p class="mb-3">${dayName}</p>
                    <h6 class="mt-4 f-s-13"> +${day[0].t}°C</h6>
                     <span>
                        <img src="${day[0].image}" class="weather-svg" alt="weather" width="25">
                    </span>
                    <p class="f-s-13 mt-3"><i class="wi wi-raindrop"></i> ${day[0].hu}%</p>
                </div>
            `;
            }).join('');

            forecastHtml.innerHTML = weatherHtml;
        })
        .catch(error => {
            currentTemp.innerHTML = 'N/A';
            weatherForecast.innerHTML = '<div class="p-3 text-danger">Failed to load weather data</div>';
            console.error('Weather fetch error:', error);
        });
</script>
