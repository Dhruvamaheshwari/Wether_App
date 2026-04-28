const userLocation = document.getElementById("userLocation"),
      converter = document.getElementById("converter"),
      weatherIcon = document.querySelector(".weatherIcon"),
      temperature = document.querySelector(".temperature"),
      feelsLike = document.querySelector(".feelsLike"),
      description = document.querySelector(".description"),
      date = document.querySelector(".date"),
      city = document.querySelector(".city"),
      HValue = document.getElementById("HValue"),
      WValue = document.getElementById("WValue"),
      SRValue = document.getElementById("SRValue"),
      SSValue = document.getElementById("SSValue"),
      CValue = document.getElementById("CValue"),
      UVValue = document.getElementById("UVValue"),
      PValue = document.getElementById("PValue"),
      Forecast = document.querySelector(".Forecast");

const apiKey = "";


function findLocation() {
    const cityName = userLocation.value.trim();
    if (!cityName) return alert("Please enter a city name.");

    // Clear previous forecast
    Forecast.innerHTML = "";

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityName}&appid=${apiKey}&units=metric`)
        .then(res => res.json())
        .then(current => {
            if (current.cod !== 200) return alert(current.message);

            // Update current weather
            city.innerHTML = `${current.name}, ${current.sys.country}`;
            weatherIcon.style.backgroundImage = `url(https://openweathermap.org/img/wn/${current.weather[0].icon}@2x.png)`;
            temperature.innerHTML = TemConverter(current.main.temp);
            feelsLike.innerHTML = "Feels like " + current.main.feels_like;
            description.innerHTML = `<i class="fa-brands fa-cloudversify"></i> &nbsp;${current.weather[0].description}`;

            const options = { weekday: "long", month: "long", day: "numeric", hour: "numeric", minute: "numeric", hour12: true };
            date.innerHTML = getLongFomateDateTime(current.dt, current.timezone, options);
            HValue.innerHTML = Math.round(current.main.humidity) + "<span>%</span>";
            WValue.innerHTML = Math.round(current.wind.speed) + "<span>m/s</span>";
            CValue.innerHTML = current.clouds.all + "<span>%</span>";
            PValue.innerHTML = current.main.pressure + "<span>hPa</span>";

            const timeOpts = { hour: "numeric", minute: "numeric", hour12: true };
            SRValue.innerHTML = getLongFomateDateTime(current.sys.sunrise, current.timezone, timeOpts);
            SSValue.innerHTML = getLongFomateDateTime(current.sys.sunset, current.timezone, timeOpts);

            // Fetch weekly forecast
            fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${cityName}&appid=${apiKey}&units=metric`)
                .then(res => res.json())
                .then(data => {
                    const weeklyData = getWeeklyDataFromHourly(data.list);
                    displayWeeklyData(weeklyData);
                });
        });


        //fetch the uv index
        // fetch(`https://api.weatherapi.com/v1/current.json?key=${apiKeyUvIndex}&q=${cityName}`
        // )
        // .then((response) =>{
        //     console.log(response.status);
        //     return response.json();
        //     //location.current.uv;
        // })
        // .then((data) => {
        //     console.log(data);
        //     UVValue.innerHTML = data.current.uv;
        // });
        



}

function TemConverter(temp) {
    let tempValue = Math.round(temp);
    return converter.value == "°C"
        ? `${tempValue}<span>°C</span>`
        : `${Math.round(tempValue * 9 / 5 + 32)}<span>°F</span>`;
}

function getLongFomateDateTime(dtValue, offset, options = {}) {
    const date = new Date((dtValue + offset) * 1000);
    return date.toLocaleString("en-US", { timeZone: "UTC", ...options });
}

function getWeeklyDataFromHourly(hourlyData) {
    const dailyData = {};
    hourlyData.forEach(item => {
        const date = new Date(item.dt * 1000);
        const dayKey = date.toISOString().split("T")[0];
        if (!dailyData[dayKey]) {
            dailyData[dayKey] = {
                temps: [],
                icon: item.weather[0].icon,
                desc: item.weather[0].description
            };
        }
        dailyData[dayKey].temps.push(item.main.temp);
    });

    return Object.keys(dailyData).map(date => {
        const temps = dailyData[date].temps;
        const avgTemp = temps.reduce((a, b) => a + b, 0) / temps.length;
        return {
            date,
            avgTemp: Math.round(avgTemp),
            icon: dailyData[date].icon,
            desc: dailyData[date].desc
        };
    }).slice(0, 7); // Only show 7 days
}

function displayWeeklyData(weeklyData) {
    let forecastHTML = "";
    weeklyData.forEach(item => {
        const dayName = new Date(item.date).toLocaleDateString("en-US", { weekday: "long" });
        const iconUrl = `https://openweathermap.org/img/wn/${item.icon}@2x.png`;
        forecastHTML += `
            <div>
                <h3 class="font-bold">${dayName}</h3>
                <img src="${iconUrl}" alt="icon" class="w-[50px] h-[50px] mx-auto cursor-pointer">
                <p class="forecast-desc">${item.desc}</p>
                <p><strong>${item.avgTemp}</strong>°C</p>
            </div>
        `;
    });
    Forecast.innerHTML = forecastHTML;
}

// userLocation.addEventListener("Enter", function (e) {
//     if (e.key === "Enter") {
//         findLocation();
//     }
// });


//now we are doing the functionalities for the default location//
var weatherData ;
window.onload = function(){
   
        const cityName = "delhi";
      
    
        // Clear previous forecast
        Forecast.innerHTML = "";
    
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${cityName}&appid=${apiKey}&units=metric`)
            .then(res => res.json())
            .then(current => {
                if (current.cod !== 200) return alert(current.message);
                console.log(current);
    
                // Update current weather
                city.innerHTML = `${current.name}, ${current.sys.country}`;
                weatherIcon.style.backgroundImage = `url(https://openweathermap.org/img/wn/${current.weather[0].icon}@2x.png)`;
                temperature.innerHTML = TemConverter(current.main.temp);
                feelsLike.innerHTML = "Feels like " + current.main.feels_like;
                description.innerHTML = `<i class="fa-brands fa-cloudversify"></i> &nbsp;${current.weather[0].description}`;
    
                const options = { weekday: "long", month: "long", day: "numeric", hour: "numeric", minute: "numeric", hour12: true };
                date.innerHTML = getLongFomateDateTime(current.dt, current.timezone, options);
                HValue.innerHTML = Math.round(current.main.humidity) + "<span>%</span>";
                WValue.innerHTML = Math.round(current.wind.speed) + "<span>m/s</span>";
                CValue.innerHTML = current.clouds.all + "<span>%</span>";
                PValue.innerHTML = current.main.pressure + "<span>hPa</span>";

                //weather data
                weatherData = current.weather[0].main;
    
                const timeOpts = { hour: "numeric", minute: "numeric", hour12: true };
                SRValue.innerHTML = getLongFomateDateTime(current.sys.sunrise, current.timezone, timeOpts);
                SSValue.innerHTML = getLongFomateDateTime(current.sys.sunset, current.timezone, timeOpts);
    
                // Fetch weekly forecast
                fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${cityName}&appid=${apiKey}&units=metric`)
                    .then(res => res.json())
                    .then(data => {
                        const weeklyData = getWeeklyDataFromHourly(data.list);
                        displayWeeklyData(weeklyData);
                    });
            });
    
    
    function TemConverter(temp) {
        let tempValue = Math.round(temp);
        return converter.value == "°C"
            ? `${tempValue}<span>°C</span>`
            : `${Math.round(tempValue * 9 / 5 + 32)}<span>°F</span>`;
    }
    
    function getLongFomateDateTime(dtValue, offset, options = {}) {
        const date = new Date((dtValue + offset) * 1000);
        return date.toLocaleString("en-US", { timeZone: "UTC", ...options });
    }
    
    function getWeeklyDataFromHourly(hourlyData) {
        const dailyData = {};
        hourlyData.forEach(item => {
            const date = new Date(item.dt * 1000);
            const dayKey = date.toISOString().split("T")[0];
            if (!dailyData[dayKey]) {
                dailyData[dayKey] = {
                    temps: [],
                    icon: item.weather[0].icon,
                    desc: item.weather[0].description
                };
            }
            dailyData[dayKey].temps.push(item.main.temp);
        });
    
        return Object.keys(dailyData).map(date => {
            const temps = dailyData[date].temps;
            const avgTemp = temps.reduce((a, b) => a + b, 0) / temps.length;
            return {
                date,
                avgTemp: Math.round(avgTemp),
                icon: dailyData[date].icon,
                desc: dailyData[date].desc
            };
        }).slice(0, 7); // Only show 7 days
    }
    
    function displayWeeklyData(weeklyData) {
        let forecastHTML = "";
        weeklyData.forEach(item => {
            const dayName = new Date(item.date).toLocaleDateString("en-US", { weekday: "long" });
            const iconUrl = `https://openweathermap.org/img/wn/${item.icon}@2x.png`;
            forecastHTML += `
                <div>
                    <h3 class="font-bold">${dayName}</h3>
                    <img src="${iconUrl}" alt="icon" class="w-[50px] h-[50px] mx-auto cursor-pointer">
                    <p class="forecast-desc">${item.desc}</p>
                    <p><strong>${item.avgTemp}</strong>°C</p>
                </div>
            `;
        });
        Forecast.innerHTML = forecastHTML;
    }



    // fetch(`https://api.weatherapi.com/v1/current.json?key=${apiKeyUvIndex}&q=delhi`
    // )
    // .then((response) =>{
    //     console.log(response.status);
    //     return response.json();
    //     //location.current.uv;
    // })
    // .then((data) => {
    //     console.log(data);
    //     UVValue.textContent = data.current.uv;
    // });
    
  
}


