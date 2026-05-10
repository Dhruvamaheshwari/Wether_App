const weatherPage = document.body;
const weatherUrl = weatherPage.dataset.weatherUrl;
const defaultCity = weatherPage.dataset.defaultCity || "Delhi";

const userLocation = document.getElementById("userLocation");
const converter = document.getElementById("converter");
const weatherIcon = document.querySelector(".weatherIcon");
const temperature = document.querySelector(".temperature");
const feelsLike = document.querySelector(".feelsLike");
const description = document.querySelector(".description");
const dateText = document.querySelector(".date");
const city = document.querySelector(".city");
const humidityValue = document.getElementById("HValue");
const windValue = document.getElementById("WValue");
const sunriseValue = document.getElementById("SRValue");
const sunsetValue = document.getElementById("SSValue");
const cloudValue = document.getElementById("CValue");
const pressureValue = document.getElementById("PValue");
const forecastContainer = document.querySelector(".Forecast");
const recommendationText = document.getElementById("recommendationText");

let currentPayload = null;

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#39;");
}

function formatTemperature(value) {
    const numericValue = Number(value ?? 0);

    if (converter.value === "°F") {
        return `${Math.round((numericValue * 9) / 5 + 32)}<span>°F</span>`;
    }

    return `${Math.round(numericValue)}<span>°C</span>`;
}

function formatDateTime(timestamp, timezoneOffset, options) {
    const utcDate = new Date((timestamp + timezoneOffset) * 1000);
    return utcDate.toLocaleString("en-US", {
        timeZone: "UTC",
        ...options,
    });
}

function renderRecommendations(recommendations = []) {
    if (!Array.isArray(recommendations) || recommendations.length === 0) {
        recommendationText.innerHTML = "<p>No farming recommendations available right now.</p>";
        return;
    }

    recommendationText.innerHTML = recommendations
        .map((item) => `<p>${escapeHtml(item)}</p>`)
        .join("");
}

function renderForecast(forecast = []) {
    if (!Array.isArray(forecast) || forecast.length === 0) {
        forecastContainer.innerHTML = "<div><p>Forecast unavailable.</p></div>";
        return;
    }

    forecastContainer.innerHTML = forecast
        .map((item) => {
            const dayName = new Date(item.date).toLocaleDateString("en-US", { weekday: "long" });
            const iconUrl = item.icon
                ? `https://openweathermap.org/img/wn/${item.icon}@2x.png`
                : "";

            return `
                <div>
                    <h3 class="font-bold">${escapeHtml(dayName)}</h3>
                    <img src="${iconUrl}" alt="icon" class="forecast-icon">
                    <p class="forecast-desc">${escapeHtml(item.description)}</p>
                    <p><strong>${formatTemperature(item.avg_temp)}</strong></p>
                </div>
            `;
        })
        .join("");
}

function renderWeather(payload) {
    currentPayload = payload;

    const { city: cityData, current, forecast, recommendations } = payload;

    city.textContent = `${cityData.name}, ${cityData.country}`;
    temperature.innerHTML = formatTemperature(current.temperature);
    feelsLike.innerHTML = `Feels like ${formatTemperature(current.feels_like)}`;
    description.textContent = current.description;
    dateText.textContent = formatDateTime(current.timestamp, current.timezone, {
        weekday: "long",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    });

    humidityValue.innerHTML = `${Math.round(current.humidity)}<span>%</span>`;
    windValue.innerHTML = `${Math.round(current.wind_speed)}<span>m/s</span>`;
    cloudValue.innerHTML = `${current.clouds}<span>%</span>`;
    pressureValue.innerHTML = `${current.pressure}<span>hPa</span>`;
    sunriseValue.textContent = formatDateTime(current.sunrise, current.timezone, {
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    });
    sunsetValue.textContent = formatDateTime(current.sunset, current.timezone, {
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    });

    weatherIcon.style.backgroundImage = current.icon
        ? `url(https://openweathermap.org/img/wn/${current.icon}@2x.png)`
        : "none";

    renderForecast(forecast);
    renderRecommendations(recommendations);
}

function clearWeatherCards(message) {
    weatherIcon.style.backgroundImage = "none";
    temperature.innerHTML = "--<span>°C</span>";
    feelsLike.textContent = "Feels like --";
    description.textContent = message;
    dateText.textContent = "";
    city.textContent = "";
    humidityValue.textContent = "";
    windValue.textContent = "";
    cloudValue.textContent = "";
    pressureValue.textContent = "";
    sunriseValue.textContent = "";
    sunsetValue.textContent = "";
    forecastContainer.innerHTML = "<div><p>Forecast unavailable.</p></div>";
}

async function loadWeather(cityName) {
    const targetCity = cityName?.trim() || defaultCity;

    clearWeatherCards("Loading weather...");

    try {
        const response = await fetch(`${weatherUrl}?city=${encodeURIComponent(targetCity)}`, {
            headers: {
                Accept: "application/json",
            },
        });

        const payload = await response.json();

        if (!response.ok) {
            throw new Error(payload.message || "Unable to load weather data.");
        }

        renderWeather(payload);
    } catch (error) {
        currentPayload = null;
        renderRecommendations([]);
        clearWeatherCards(error.message || "Unable to load weather data.");
    }
}

function findLocation() {
    const cityName = userLocation.value.trim();

    if (!cityName) {
        clearWeatherCards("Please enter a city name.");
        renderRecommendations([]);
        return;
    }

    loadWeather(cityName);
}

window.findLocation = findLocation;

userLocation.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
        findLocation();
    }
});

converter.addEventListener("change", () => {
    if (currentPayload) {
        renderWeather(currentPayload);
    }
});

window.addEventListener("load", () => {
    loadWeather(defaultCity);
});
